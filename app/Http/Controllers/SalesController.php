<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use App\Models\TransactionDetail;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of the sales.
     */

    public function index()
    {
        $sales = SalesTransaction::with('customer', 'user', 'details.product')->get();
        return view('sales.index', compact('sales'));
    }


    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $customers = Customer::all();
        $users = User::all();

        // Only include products that are not expired and have stock
        $products = Product::where(function($query) {
            $query->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', Carbon::today());
        })->where('Quantity_in_Stock', '>', 0)->get();

        return view('sales.create', compact('customers', 'products', 'users'));
    }

    /**
     * Store a newly created sale in storage.
     */
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

        // TEMPORARY DEBUG - REMOVE AFTER TESTING
        \Log::info('=== SALE DEBUG ===');
        \Log::info('Products data:', $request->products);
        \Log::info('First product Quantity: ' . ($request->products[0]['Quantity'] ?? 'NOT SET'));
        \Log::info('First product Kilo: ' . ($request->products[0]['Kilo'] ?? 'NOT SET'));
        
        // Also stop execution and show on screen
        echo "<h1>DEBUG - Check what's being sent:</h1>";
        echo "<pre>";
        echo "First product Quantity: " . ($request->products[0]['Quantity'] ?? 'NOT SET') . "\n";
        echo "First product Kilo: " . ($request->products[0]['Kilo'] ?? 'NOT SET') . "\n";
        echo "\nFull products array:\n";
        print_r($request->products);
        echo "</pre>";
        die();
        // END DEBUG

        // Check for expired, out-of-stock products, and stock limits
        foreach ($request->products as $item) {
            $product = Product::find($item['Product_ID']);
            if (!$product) {
                return back()->withInput()->withErrors('Invalid product selected.');
            }
            if (($product->expiry_date && $product->expiry_date < Carbon::today()) || $product->Quantity_in_Stock <= 0) {
                return back()->withInput()->withErrors('One of the selected products is expired or out of stock and cannot be sold.');
            }
            
            // Check if QUANTITY exceeds available stock (NOT kilo)
            $quantityToSell = $item['Quantity'];
            if ($quantityToSell > $product->Quantity_in_Stock) {
                return back()->withInput()->withErrors([
                    'products' => "Cannot sell {$quantityToSell} quantity of {$product->Product_Name}. Only {$product->Quantity_in_Stock} available in stock."
                ]);
            }
        }

        // Calculate total_amount based on Kilo × Price
        $totalAmount = 0;
        foreach ($request->products as $product) {
            $totalAmount += $product['Kilo'] * $product['Price'];
        }

        // Create the sale with total_amount
        $sale = SalesTransaction::create([
            'Customer_ID' => $request->Customer_ID,
            'User_ID' => $request->User_ID,
            'payment_method' => $request->payment_method,
            'total_amount' => $totalAmount,
        ]);

        // Create transaction details AND deduct from stock
        foreach ($request->products as $productData) {
            // Create the transaction detail record
            TransactionDetail::create([
                'transaction_ID' => $sale->transaction_ID,
                'Product_ID' => $productData['Product_ID'],
                'Quantity' => $productData['Quantity'],
                'Kilo' => $productData['Kilo'],
                'Price' => $productData['Price'],
            ]);
            
            // *** DEDUCT QUANTITY FROM STOCK ***
            $product = Product::find($productData['Product_ID']);
            $product->Quantity_in_Stock -= $productData['Quantity'];
            $product->save();
        }

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(SalesTransaction $sale)
    {
        $customers = Customer::all();
        $users = User::all();

        // Only include products that are not expired and have stock
        $products = Product::where(function($query) {
            $query->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', Carbon::today());
        })->where('Quantity_in_Stock', '>', 0)->get();

        $sale->load('details.product'); // eager load details with products
        return view('sales.edit', compact('sale', 'customers', 'products', 'users'));
    }

    /**
     * Update the specified sale in storage.
     */
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
        ]);

        // First, restore the old quantities back to stock
        foreach ($sale->details as $oldDetail) {
            $product = Product::find($oldDetail->Product_ID);
            $product->Quantity_in_Stock += $oldDetail->Quantity;
            $product->save();
        }

        // Check for expired, out-of-stock products, and stock limits with NEW quantities
        foreach ($request->products as $item) {
            $product = Product::find($item['Product_ID']);
            if (!$product) {
                return back()->withInput()->withErrors('Invalid product selected.');
            }
            if (($product->expiry_date && $product->expiry_date < Carbon::today()) || $product->Quantity_in_Stock <= 0) {
                return back()->withInput()->withErrors('One of the selected products is expired or out of stock and cannot be sold.');
            }
            
            // Check if QUANTITY exceeds available stock (NOT kilo)
            $quantityToSell = $item['Quantity'];
            if ($quantityToSell > $product->Quantity_in_Stock) {
                return back()->withInput()->withErrors([
                    'products' => "Cannot sell {$quantityToSell} quantity of {$product->Product_Name}. Only {$product->Quantity_in_Stock} available in stock."
                ]);
            }
        }

        // Calculate total_amount based on Kilo × Price
        $totalAmount = 0;
        foreach ($request->products as $product) {
            $totalAmount += $product['Kilo'] * $product['Price'];
        }

        // Update the sale with total_amount
        $sale->update([
            'Customer_ID' => $request->Customer_ID,
            'User_ID' => $request->User_ID,
            'payment_method' => $request->payment_method,
            'total_amount' => $totalAmount,
        ]);

        // Delete old transaction details
        $sale->details()->delete();

        // Create new transaction details AND deduct new quantities from stock
        foreach ($request->products as $productData) {
            // Create the transaction detail record
            TransactionDetail::create([
                'transaction_ID' => $sale->transaction_ID,
                'Product_ID' => $productData['Product_ID'],
                'Quantity' => $productData['Quantity'],
                'Kilo' => $productData['Kilo'],
                'Price' => $productData['Price'],
            ]);
            
            // *** DEDUCT NEW QUANTITY FROM STOCK ***
            $product = Product::find($productData['Product_ID']);
            $product->Quantity_in_Stock -= $productData['Quantity'];
            $product->save();
        }

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(SalesTransaction $sale)
    {
        // Restore quantities back to stock before deleting
        foreach ($sale->details as $detail) {
            $product = Product::find($detail->Product_ID);
            $product->Quantity_in_Stock += $detail->Quantity;
            $product->save();
        }
        
        $sale->details()->delete(); // remove related transaction details
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}