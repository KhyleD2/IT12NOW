<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Show create form
    public function create()
    {
        return view('products.create');
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);

        Product::create($request->only('name','price'));

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Edit product form
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);

        $product->update($request->only('name','price'));
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
public function sales()
{
    return $this->hasMany(Sale::class, 'Product_ID', 'Product_ID');
}

public function returnedItems()
{
    return $this->hasMany(ReturnedItem::class, 'Product_ID', 'Product_ID');
}

public function getIsLowStockAttribute()
{
    return $this->Quantity_in_Stock <= $this->reorder_level;
}

public function getIsExpiringSoonAttribute()
{
    if (!$this->expiry_date) return false;
    return $this->expiry_date->isBetween(now(), now()->addDays(7));
}

public function scopeLowStock($query)
{
    return $query->whereRaw('Quantity_in_Stock <= reorder_level');
}

public function scopeExpiringSoon($query)
{
    return $query->whereBetween('expiry_date', [now(), now()->addDays(7)]);
}

public function scopeByCategory($query, $category)
{
    return $query->where('Category', $category);
}

}
