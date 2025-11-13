@extends('layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-2xl shadow-lg">

    <h1 class="text-3xl font-bold text-green-700 mb-6">Add Product</h1>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Product Name</label>
            <input type="text" name="name" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <div>
            <label class="block font-semibold mb-1">Price</label>
            <input type="number" name="price" min="0" step="0.01" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
                Add Product
            </button>
        </div>
    </form>
</div>
@endsection
