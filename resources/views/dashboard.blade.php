@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Inventory</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalProducts ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Customers</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalCustomers ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Suppliers</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalSuppliers ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Sales</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalSales ?? 0 }}</p>
    </div>
</div>
@endsection
