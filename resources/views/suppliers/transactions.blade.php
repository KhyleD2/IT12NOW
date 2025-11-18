@extends('layouts.app')

@section('title', 'Supplier Transactions')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-green-700">Supplier Transactions</h1>
</div>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="overflow-x-auto bg-white p-4 rounded shadow">
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-green-700 text-white">
                <th class="px-4 py-2">Transaction ID</th>
                <th class="px-4 py-2">Supplier</th>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Quantity</th>
                <th class="px-4 py-2">Supply Date</th>
                <th class="px-4 py-2">Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2">{{ $transaction->Supply_transac_ID }}</td>
                    <td class="px-4 py-2">{{ $transaction->supplier->Supplier_Name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $transaction->product->Product_Name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $transaction->quantity_supplier }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($transaction->supply_date)->format('M d, Y') }}</td>
                    <td class="px-4 py-2">₱{{ number_format($transaction->total_cost, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
