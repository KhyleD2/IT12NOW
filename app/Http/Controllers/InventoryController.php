<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('supplier')->get();
        return view('inventory.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('inventory.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Product_Name' => 'required',
            'Category' => 'required',
            'Quantity_in_Stock' => 'required|integer',
            'unit_price' => 'required|numeric',
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
        ]);

        Product::create($request->all());
        return redirect()->route('inventory.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $inventory)
    {
        $suppliers = Supplier::all();
        return view('inventory.edit', compact('inventory', 'suppliers'));
    }

    public function update(Request $request, Product $inventory)
    {
        $request->validate([
            'Product_Name' => 'required',
            'Category' => 'required',
            'Quantity_in_Stock' => 'required|integer',
            'unit_price' => 'required|numeric',
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
        ]);

        $inventory->update($request->all());
        return redirect()->route('inventory.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully.');
    }
}
