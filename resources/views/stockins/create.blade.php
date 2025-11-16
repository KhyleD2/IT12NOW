@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Add Stock</h1>

@if ($errors->any())
<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('stockins.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Product</label>
        <select name="Product_ID" id="product_select" class="w-full border p-2 rounded" required>
            <option value="">-- Select Product --</option>
            @foreach($products as $product)
                <option value="{{ $product->Product_ID }}">
                    {{ $product->Product_Name }}@if($product->variety) - {{ $product->variety }}@endif ({{ $product->Category }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Date</label>
        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
        <input type="number" name="quantity" class="w-full border p-2 rounded" min="1" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Price per Unit</label>
        <input type="number" step="0.01" name="price" class="w-full border p-2 rounded" min="0" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Unit (e.g., kg, pcs, box)</label>
        <input type="text" name="unit" placeholder="kg" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Expiry Date (Optional)</label>
        <input type="date" name="expiry_date" class="w-full border p-2 rounded" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Critical Level</label>
        <input type="number" name="critical_level" value="5" class="w-full border p-2 rounded" min="0" required>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Stock</button>
        <a href="{{ route('stockins.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
    </div>
</form>
@endsection