<?php
// app/Http/Controllers/InventoryController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with(['supplier', 'stockIns'])->get();
        return view('inventory.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('inventory.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Product_Name' => 'required|string|max:255',
            'Category' => 'required|string',
            'variety' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Set initial values (will be updated by stock-ins)
        $validated['Quantity_in_Stock'] = 0;
        $validated['unit_price'] = 0;
        $validated['reorder_level'] = 5;

        Product::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Product added successfully! Now add stock for this product.');
    }

    public function edit(Product $inventory)
    {
        $suppliers = Supplier::all();
        return view('inventory.edit', compact('inventory', 'suppliers'));
    }

    public function update(Request $request, Product $inventory)
    {
        $validated = $request->validate([
            'Product_Name' => 'required|string|max:255',
            'Category' => 'required|string',
            'variety' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')
            ->with('success', 'Product deleted successfully!');
    }
}