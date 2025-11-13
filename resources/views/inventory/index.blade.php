@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Inventory</h1>

<a href="{{ route('inventory.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Add Product</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<table class="min-w-full bg-white rounded shadow">
    <thead>
        <tr class="bg-green-700 text-white">
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Product Name</th>
            <th class="py-2 px-4">Category</th>
            <th class="py-2 px-4">Stock</th>
            <th class="py-2 px-4">Unit Price</th>
            <th class="py-2 px-4">Supplier</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr class="border-t">
            <td class="py-2 px-4">{{ $product->Product_ID }}</td>
            <td class="py-2 px-4">{{ $product->Product_Name }}</td>
            <td class="py-2 px-4">{{ $product->Category }}</td>
            <td class="py-2 px-4">{{ $product->Quantity_in_Stock }}</td>
            <td class="py-2 px-4">{{ number_format($product->unit_price,2) }}</td>
            <td class="py-2 px-4">{{ $product->supplier->Supplier_Name }}</td>
            <td class="py-2 px-4 flex gap-2">
                <a href="{{ route('inventory.edit', $product->Product_ID) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                <form action="{{ route('inventory.destroy', $product->Product_ID) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
