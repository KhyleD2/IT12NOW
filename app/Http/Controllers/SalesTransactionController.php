<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use App\Models\TransactionDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

class SalesTransactionController extends Controller
{
    // List all transactions - FIXED: Only show transactions with valid relationships
    public function index()
    {
        $transactions = SalesTransaction::with(['customer', 'user', 'details.product'])
            ->whereHas('customer')
            ->whereHas('user')
            ->orderBy('transaction_ID', 'desc')
            ->get();
        
        return view('sales.index', compact('transactions'));
    }

    // Show create form
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all(); // Cashiers
        return view('sales.create', compact('customers', 'products', 'users'));
    }

    // Store a new transaction
    public function store(Request $request)
    {
        $request->validate([
            'Customer_ID' => 'required|exists:customers,Customer_ID',
            'User_ID' => 'required|exists:users,User_ID',
            'payment_method' => 'required|in:Cash,GCash',
            'products.*.Product_ID' => 'required|exists:products,Product_ID',
            'products.*.Quantity' => 'required|numeric|min:0.1',
            'products.*.Kilo' => 'required|numeric|min:0.1',
            'products.*.Price' => 'required|numeric|min:0',
        ]);

        // Calculate total based on Kilo × Price
        $total = 0;
        foreach ($request->products as $p) {
            $kilo = $p['Kilo'];
            $total += $kilo * $p['Price'];
        }

        // Create transaction
        $transaction = SalesTransaction::create([
            'Customer_ID' => $request->Customer_ID,
            'User_ID' => $request->User_ID,
            'transaction_date' => now(),
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'receipt_number' => 'RCPT-' . time(),
            'status' => 'pending',
        ]);

        // Save transaction details and update stock
        foreach ($request->products as $p) {
            // FIXED: Use Kilo for pricing but Quantity for stock deduction
            $kilo = $p['Kilo'];
            $quantity = $p['Quantity']; // THIS deducts from stock
            
            TransactionDetail::create([
                'transaction_ID' => $transaction->transaction_ID,
                'Product_ID' => $p['Product_ID'],
                'Quantity' => $kilo, // Save kilo in the transaction detail
                'unit_price' => $p['Price'],
            ]);

            // CRITICAL FIX: Deduct by QUANTITY, not by Kilo
            $product = Product::find($p['Product_ID']);
            if ($product) {
                $product->decrement('Quantity_in_Stock', $quantity);
            }
        }

        return redirect()->route('sales.index')->with('success', 'Transaction recorded successfully.');
    }

    // Show edit form
    public function edit(SalesTransaction $sale)
    {
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();
        return view('sales.edit', compact('sale', 'customers', 'products', 'users'));
    }

    // Update transaction
    public function update(Request $request, SalesTransaction $sale)
    {
        $request->validate([
            'Customer_ID' => 'required|exists:customers,Customer_ID',
            'User_ID' => 'required|exists:users,User_ID',
            'payment_method' => 'required|in:Cash,GCash',
            'products.*.Product_ID' => 'required|exists:products,Product_ID',
            'products.*.Quantity' => 'required|numeric|min:0.1',
            'products.*.Kilo' => 'required|numeric|min:0.1',
            'products.*.Price' => 'required|numeric|min:0',
            'status' => 'nullable|in:pending,paid',
        ]);

        // First, restore old stock quantities
        foreach ($sale->details as $oldDetail) {
            $product = Product::find($oldDetail->Product_ID);
            if ($product) {
                // Restore the quantity that was previously deducted
                $product->increment('Quantity_in_Stock', $oldDetail->Quantity);
            }
        }

        // Update basic transaction info
        $sale->update([
            'Customer_ID' => $request->Customer_ID,
            'User_ID' => $request->User_ID,
            'payment_method' => $request->payment_method,
            'status' => $request->status ?? $sale->status,
        ]);

        // If products are being updated, handle transaction details
        if ($request->has('products')) {
            // Delete old details
            $sale->details()->delete();
            
            // Calculate new total
            $total = 0;
            
            // Create new details and deduct new stock
            foreach ($request->products as $p) {
                $kilo = $p['Kilo'];
                $quantity = $p['Quantity'];
                $lineTotal = $kilo * $p['Price'];
                $total += $lineTotal;
                
                TransactionDetail::create([
                    'transaction_ID' => $sale->transaction_ID,
                    'Product_ID' => $p['Product_ID'],
                    'Quantity' => $kilo, // Save kilo in transaction detail
                    'unit_price' => $p['Price'],
                ]);

                // CRITICAL FIX: Deduct by QUANTITY, not by Kilo
                $product = Product::find($p['Product_ID']);
                if ($product) {
                    $product->decrement('Quantity_in_Stock', $quantity);
                }
            }
            
            // Update total amount
            $sale->update(['total_amount' => $total]);
        }

        return redirect()->route('sales.index')->with('success', 'Transaction updated successfully.');
    }

    // Mark pending transaction as paid
    public function markPaid(SalesTransaction $sale)
    {
        $sale->update(['status' => 'paid']);
        return redirect()->route('sales.printReceipt', $sale->transaction_ID);
    }

    // Print receipt
    public function printReceipt(SalesTransaction $sale)
    {
        $sale->load(['customer', 'user', 'details.product']);
        return view('sales.receipt', compact('sale'));
    }

    // Delete transaction and restore stock
    public function destroy(SalesTransaction $sale)
    {
        foreach ($sale->details as $detail) {
            $product = $detail->product;
            if ($product) {
                $product->increment('Quantity_in_Stock', $detail->Quantity);
            }
        }

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Transaction deleted successfully.');
    }

    // Get transaction details for modal view
    public function details(SalesTransaction $sale)
    {
        $sale->load(['customer', 'user', 'details.product']);
        
        // Format the response
        $response = [
            'transaction_ID' => $sale->transaction_ID,
            'receipt_number' => $sale->receipt_number,
            'transaction_date' => $sale->transaction_date,
            'total_amount' => $sale->total_amount,
            'payment_method' => $sale->payment_method,
            'status' => $sale->status,
            'customer' => [
                'Customer_Name' => $sale->customer->Customer_Name ?? 'N/A'
            ],
            'user' => [
                'fname' => $sale->user->fname ?? 'N/A',
                'lname' => $sale->user->lname ?? ''
            ],
            'details' => $sale->details->map(function($detail) {
                return [
                    'Quantity' => $detail->Quantity,
                    'unit_price' => $detail->unit_price,
                    'product' => [
                        'Product_Name' => $detail->product->Product_Name . 
                            ($detail->product->variety ? ' - ' . $detail->product->variety : '')
                    ]
                ];
            })
        ];
        
        return response()->json($response);
    }
}