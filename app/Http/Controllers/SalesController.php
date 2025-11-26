<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of the sales.
     */
    public function index()
    {
        $sales = SalesTransaction::with('customer', 'user', 'products')->get();
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

        // Check for expired, out-of-stock products, and stock limits
        foreach ($request->products as $item) {
            $product = Product::find($item['Product_ID']);
            if (!$product) {
                return back()->withInput()->withErrors('Invalid product selected.');
            }
            if (($product->expiry_date && $product->expiry_date < Carbon::today()) || $product->Quantity_in_Stock <= 0) {
                return back()->withInput()->withErrors('One of the selected products is expired or out of stock and cannot be sold.');
            }
            
            // Check if kilo exceeds available stock
            $kiloToSell = $item['Kilo'];
            if ($kiloToSell > $product->Quantity_in_Stock) {
                return back()->withInput()->withErrors([
                    'products' => "Cannot sell {$kiloToSell} kg of {$product->Product_Name}. Only {$product->Quantity_in_Stock} kg available in stock."
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

        // Attach products to the sale
        foreach ($request->products as $product) {
            $sale->products()->attach($product['Product_ID'], [
                'Quantity' => $product['Quantity'],
                'Kilo' => $product['Kilo'],
                'Price' => $product['Price']
            ]);
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

        $sale->load('products'); // eager load products
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

        // Check for expired or out-of-stock products
        foreach ($request->products as $item) {
            $product = Product::find($item['Product_ID']);
            if (!$product) {
                return back()->withErrors('Invalid product selected.');
            }
            if (($product->expiry_date && $product->expiry_date < Carbon::today()) || $product->Quantity_in_Stock <= 0) {
                return back()->withErrors('One of the selected products is expired or out of stock and cannot be sold.');
            }
            
            // Check if kilo exceeds available stock
            $kiloToSell = $item['Kilo'];
            if ($kiloToSell > $product->Quantity_in_Stock) {
                return back()->withErrors([
                    'products' => "Cannot sell {$kiloToSell} kg of {$product->Product_Name}. Only {$product->Quantity_in_Stock} kg available in stock."
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

        // Sync products with quantity, kilo, and price
        $syncData = [];
        foreach ($request->products as $product) {
            $syncData[$product['Product_ID']] = [
                'Quantity' => $product['Quantity'],
                'Kilo' => $product['Kilo'],
                'Price' => $product['Price']
            ];
        }
        $sale->products()->sync($syncData);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(SalesTransaction $sale)
    {
        $sale->products()->detach(); // remove related products
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}