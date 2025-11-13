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
    // List all transactions
    public function index()
    {
        $transactions = SalesTransaction::with(['customer', 'user', 'details.product'])->get();
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
            'products.*.Price' => 'required|numeric|min:0',
        ]);

        // Calculate total
        $total = 0;
        foreach ($request->products as $p) {
            $total += $p['Quantity'] * $p['Price'];
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
            TransactionDetail::create([
                'transaction_ID' => $transaction->transaction_ID,
                'Product_ID' => $p['Product_ID'],
                'Quantity' => $p['Quantity'],
                'unit_price' => $p['Price'],
            ]);

            $product = Product::find($p['Product_ID']);
            if ($product) {
                $product->decrement('Quantity_in_Stock', $p['Quantity']);
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

    // Update transaction (status only)
    public function update(Request $request, SalesTransaction $sale)
    {
        $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $sale->update(['status' => $request->status]);

        return redirect()->route('sales.index')->with('success', 'Transaction status updated.');
    }

    // Mark pending transaction as paid and redirect to print receipt
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
}
