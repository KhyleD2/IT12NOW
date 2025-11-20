@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
    <h1 class="text-3xl font-bold mb-6 text-green-700">Add Product</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-2 font-medium">Product Name</label>
            <input type="text" name="Product_Name" value="{{ old('Product_Name') }}" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Category/Type</label>
            <input type="text" name="Category" value="{{ old('Category') }}" placeholder="Tropical, Citrus, Snacks" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Variety (Optional)</label>
            <input type="text" name="variety" value="{{ old('variety') }}" placeholder="Puyat, Carabao" class="w-full p-2 border rounded">
        </div>

        <div>
            <label class="block mb-2 font-medium">Description (Optional)</label>
            <textarea name="description" placeholder="3-5 Kg Size" class="w-full p-2 border rounded" rows="3">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block mb-2 font-medium">Image (Optional)</label>
            <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">
        </div>

        <div>
            <label class="block mb-2 font-medium">Supplier</label>
            <select name="Supplier_ID" class="w-full p-2 border rounded" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->Supplier_ID }}" {{ old('Supplier_ID') == $supplier->Supplier_ID ? 'selected' : '' }}>
                        {{ $supplier->Supplier_Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">Selling Price (Retail Price)</label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-600 font-semibold">₱</span>
                <input type="number" name="unit_price" step="0.01" min="0" value="{{ old('unit_price', 0) }}" 
                       class="w-full p-2 pl-8 border-2 rounded border-green-300 bg-green-50 font-semibold" required>
            </div>
            <p class="text-xs text-green-600 mt-1">Price you sell to customers</p>
        </div>

        <div>
            <label class="block mb-2 font-medium">Reorder Level</label>
            <input type="number" name="reorder_level" value="{{ old('reorder_level', 5) }}" min="0" class="w-full p-2 border rounded" required>
            <p class="text-xs text-gray-500 mt-1">Alert when stock falls below this level</p>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <p class="text-sm text-yellow-800">
                <strong>Note:</strong> After creating the product, go to <strong>Supplier Transaction</strong> to add stock and set supplier price.
            </p>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-medium">
                Add Product
            </button>
            <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection