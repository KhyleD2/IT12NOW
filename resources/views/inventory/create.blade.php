@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Add Product</h1>

@if ($errors->any())
<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
    @csrf
    
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Product Name</label>
        <input type="text" name="Product_Name" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Category/Type</label>
        <input type="text" name="Category" placeholder="Tropical, Citrus, Snacks" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Variety (Optional)</label>
        <input type="text" name="variety" placeholder="Puyat, Carabao" class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Description (Optional)</label>
        <textarea name="description" class="w-full border p-2 rounded" rows="3" placeholder="3-5 Kg Size"></textarea>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Image (Optional)</label>
        <input type="file" name="image" accept="image/png,image/jpeg,image/jpg" class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Supplier</label>
        <select name="Supplier_ID" class="w-full border p-2 rounded" required>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->Supplier_ID }}">{{ $supplier->Supplier_Name }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4">
        <p class="text-sm text-yellow-700">
            <strong>Note:</strong> After creating the product, go to Stock-In to add initial stock quantities.
        </p>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Product</button>
        <a href="{{ route('inventory.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
    </div>
</form>
@endsection