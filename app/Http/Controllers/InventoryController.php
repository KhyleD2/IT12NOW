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
    
    // Calculate days until expiry for each product
    foreach ($products as $product) {
        if ($product->expiry_date) {
            $expiryDate = \Carbon\Carbon::parse($product->expiry_date);
            $today = \Carbon\Carbon::today();
            $product->days_until_expiry = $today->diffInDays($expiryDate, false);
        }
    }
    
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
            'Product_Name' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Quantity_in_Stock' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'expiry_date' => 'nullable|date|after:today', // NEW: Expiry date validation
        ]);

        Product::create([
            'Product_Name' => $request->Product_Name,
            'Category' => $request->Category,
            'Quantity_in_Stock' => $request->Quantity_in_Stock,
            'unit_price' => $request->unit_price,
            'Supplier_ID' => $request->Supplier_ID,
            'expiry_date' => $request->expiry_date, // NEW: Save expiry date
            'reorder_level' => 10, // Default reorder level
        ]);

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
            'Product_Name' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Quantity_in_Stock' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'expiry_date' => 'nullable|date', // NEW: Allow past dates for existing products
        ]);

        $inventory->update([
            'Product_Name' => $request->Product_Name,
            'Category' => $request->Category,
            'Quantity_in_Stock' => $request->Quantity_in_Stock,
            'unit_price' => $request->unit_price,
            'Supplier_ID' => $request->Supplier_ID,
            'expiry_date' => $request->expiry_date, // NEW: Update expiry date
            'reorder_level' => $inventory->reorder_level ?? 10,
        ]);

        return redirect()->route('inventory.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully.');
    }
}