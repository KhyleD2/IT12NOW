{{-- resources/views/stockins/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-green-700">Edit Stock</h1>
        <a href="{{ route('stockins.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Back to Stock-In
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stockins.update', $stockin->Stock_ID) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="Product_ID" class="block text-gray-700 font-bold mb-2">Product</label>
            <select name="Product_ID" id="Product_ID" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->Product_ID }}" 
                        {{ old('Product_ID', $stockin->Product_ID) == $product->Product_ID ? 'selected' : '' }}>
                        {{ $product->Product_Name }} 
                        @if($product->variety) ({{ $product->variety }}) @endif
                        - {{ $product->supplier->Supplier_Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="date" class="block text-gray-700 font-bold mb-2">Date</label>
            <input type="date" name="date" id="date" 
                value="{{ old('date', $stockin->date->format('Y-m-d')) }}" 
                class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-bold mb-2">Quantity</label>
            <input type="number" name="quantity" id="quantity" 
                value="{{ old('quantity', $stockin->quantity) }}" 
                class="shadow border rounded w-full py-2 px-3 text-gray-700" required min="1">
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 font-bold mb-2">Price per Unit</label>
            <input type="number" name="price" id="price" 
                value="{{ old('price', $stockin->price) }}" 
                step="0.01" 
                class="shadow border rounded w-full py-2 px-3 text-gray-700" required min="0">
        </div>

        <div class="mb-4">
            <label for="unit" class="block text-gray-700 font-bold mb-2">Unit (e.g., kg, pcs, box)</label>
            <input type="text" name="unit" id="unit" 
                value="{{ old('unit', $stockin->unit) }}" 
                placeholder="kg" 
                class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label for="expiry_date" class="block text-gray-700 font-bold mb-2">Expiry Date (Optional)</label>
            <input type="date" name="expiry_date" id="expiry_date" 
                value="{{ old('expiry_date', $stockin->expiry_date ? $stockin->expiry_date->format('Y-m-d') : '') }}" 
                class="shadow border rounded w-full py-2 px-3 text-gray-700">
        </div>

        <div class="mb-4">
            <label for="critical_level" class="block text-gray-700 font-bold mb-2">Critical Level</label>
            <input type="number" name="critical_level" id="critical_level" 
                value="{{ old('critical_level', $stockin->critical_level) }}" 
                class="shadow border rounded w-full py-2 px-3 text-gray-700" required min="0">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Update Stock
            </button>
            <a href="{{ route('stockins.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection