<?php
// app/Http/Controllers/StockInController.php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\Product;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with('product')->orderBy('date', 'desc')->get();
        $products = Product::with('supplier')->get();
        
        return view('stockins.index', compact('stockIns', 'products'));
    }

    public function create()
    {
        $products = Product::with('supplier')->get();
        return view('stockins.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Product_ID' => 'required|exists:products,Product_ID',
            'date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'expiry_date' => 'nullable|date|after:today',
            'critical_level' => 'required|integer|min:0'
        ]);

        // Create stock-in record
        $stockIn = StockIn::create($validated);

        // Update product totals
        $product = Product::find($validated['Product_ID']);
        $product->updateFromStockIns();

        return redirect()->route('stockins.index')
            ->with('success', 'Stock added successfully!');
    }

    // ADD THESE NEW METHODS
    public function edit(StockIn $stockin)
    {
        $products = Product::with('supplier')->get();
        return view('stockins.edit', compact('stockin', 'products'));
    }

    public function update(Request $request, StockIn $stockin)
    {
        $validated = $request->validate([
            'Product_ID' => 'required|exists:products,Product_ID',
            'date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'expiry_date' => 'nullable|date|after:today',
            'critical_level' => 'required|integer|min:0'
        ]);

        $oldProductId = $stockin->Product_ID;
        
        // Update stock-in record
        $stockin->update($validated);

        // Update the old product if product was changed
        if ($oldProductId != $validated['Product_ID']) {
            $oldProduct = Product::find($oldProductId);
            if ($oldProduct) {
                $oldProduct->updateFromStockIns();
            }
        }

        // Update the new/current product
        $product = Product::find($validated['Product_ID']);
        $product->updateFromStockIns();

        return redirect()->route('stockins.index')
            ->with('success', 'Stock updated successfully!');
    }

    public function destroy(StockIn $stockin)
    {
        $productId = $stockin->Product_ID;
        $stockin->delete();

        // Update product totals after deletion
        $product = Product::find($productId);
        if ($product) {
            $product->updateFromStockIns();
        }

        return redirect()->route('stockins.index')
            ->with('success', 'Stock record deleted successfully!');
    }
}