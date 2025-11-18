@extends('layouts.app')

@section('content')
<div class="p-6 max-w-lg mx-auto bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Supplier Transaction Receipt</h2>

    <div class="mb-2"><strong>Supplier:</strong> {{ $transaction->supplier->Supplier_Name }}</div>
    <div class="mb-2"><strong>Product:</strong> {{ $transaction->product->Product_Name }}</div>
    <div class="mb-2"><strong>Qty Units:</strong> {{ $transaction->quantity_units }}</div>
    <div class="mb-2"><strong>Qty Kilos:</strong> {{ $transaction->quantity_kilos }}</div>
    <div class="mb-2"><strong>Supply Date:</strong> {{ $transaction->supply_date }}</div>
    <div class="mb-2"><strong>Total Cost:</strong> ₱{{ number_format($transaction->total_cost,2) }}</div>
    <div class="mb-2"><strong>Status:</strong> {{ ucfirst($transaction->status) }}</div>

    <div class="mt-6 text-center">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Print Receipt
        </button>
    </div>
</div>
@endsection
