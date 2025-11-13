@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Edit Product</h1>

@if ($errors->any())
<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('inventory.update', $inventory->Product_ID) }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label>Product Name</label>
        <input type="text" name="Product_Name" value="{{ $inventory->Product_Name }}" class="w-full border p-2 rounded" required>
    </div>
    <div class="mb-4">
        <label>Category</label>
        <input type="text" name="Category" value="{{ $inventory->Category }}" class="w-full border p-2 rounded" required>
    </div>
    <div class="mb-4">
        <label>Quantity in Stock</label>
        <input type="number" name="Quantity_in_Stock" value="{{ $inventory->Quantity_in_Stock }}" class="w-full border p-2 rounded" required>
    </div>
    <div class="mb-4">
        <label>Unit Price</label>
        <input type="number" step="0.01" name="unit_price" value="{{ $inventory->unit_price }}" class="w-full border p-2 rounded" required>
    </div>
    <div class="mb-4">
        <label>Supplier</label>
        <select name="Supplier_ID" class="w-full border p-2 rounded" required>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->Supplier_ID }}" @if($supplier->Supplier_ID==$inventory->Supplier_ID) selected @endif>{{ $supplier->Supplier_Name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Product</button>
</form>
@endsection
