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

<form action="{{ route('inventory.update', $inventory->Product_ID) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Product Name</label>
        <input type="text" name="Product_Name" value="{{ $inventory->Product_Name }}" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Category/Type</label>
        <input type="text" name="Category" value="{{ $inventory->Category }}" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Variety (Optional)</label>
        <input type="text" name="variety" value="{{ $inventory->variety }}" class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Description (Optional)</label>
        <textarea name="description" class="w-full border p-2 rounded" rows="3">{{ $inventory->description }}</textarea>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Image (Optional)</label>
        @if($inventory->image)
            <img src="{{ asset('storage/' . $inventory->image) }}" alt="{{ $inventory->Product_Name }}" class="w-32 h-32 object-cover mb-2">
        @endif
        <input type="file" name="image" accept="image/png,image/jpeg,image/jpg" class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Supplier</label>
        <select name="Supplier_ID" class="w-full border p-2 rounded" required>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->Supplier_ID }}" @if($supplier->Supplier_ID==$inventory->Supplier_ID) selected @endif>
                    {{ $supplier->Supplier_Name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
        <p class="text-sm text-blue-700">
            <strong>Current Stock:</strong> {{ $inventory->Quantity_in_Stock }} units<br>
            <strong>Note:</strong> To adjust stock quantities, use Stock-In management.
        </p>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Product</button>
        <a href="{{ route('inventory.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
    </div>
</form>
@endsection