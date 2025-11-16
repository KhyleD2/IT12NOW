@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('customers.index') }}" class="text-green-600 hover:text-green-800 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Customers
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h1 class="text-3xl font-bold text-green-700 mb-4">Customer Details</h1>
    
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-gray-600 text-sm">Customer Name</p>
            <p class="text-xl font-semibold">{{ $customer->Customer_Name }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Contact Number</p>
            <p class="text-xl font-semibold">{{ $customer->Contact_Number }}</p>
        </div>
    </div>

    <div class="border-t pt-4">
        <h2 class="text-xl font-bold text-green-700 mb-2">Purchase Statistics</h2>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-green-50 p-4 rounded">
                <p class="text-gray-600 text-sm">Total Purchases</p>
                <p class="text-2xl font-bold text-green-700">{{ $customer->sales->count() }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded">
                <p class="text-gray-600 text-sm">Total Spent</p>
                <p class="text-2xl font-bold text-blue-700">₱{{ number_format($customer->sales->sum('total_amount'), 2) }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded">
                <p class="text-gray-600 text-sm">Average Purchase</p>
                <p class="text-2xl font-bold text-purple-700">₱{{ number_format($customer->sales->avg('total_amount'), 2) }}</p>
            </div>
        </div>
    </div>
</div>

<h2 class="text-2xl font-bold text-green-700 mb-4">Purchase History</h2>

@if($customer->sales && $customer->sales->count() > 0)
    @foreach($customer->sales as $sale)
        <div class="bg-white rounded-lg shadow mb-4 overflow-hidden">
            <div class="bg-green-600 text-white p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold">Receipt: {{ $sale->receipt_number }}</h3>
                        <p class="text-sm">Date: {{ \Carbon\Carbon::parse($sale->transaction_date)->format('F d, Y h:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold">₱{{ number_format($sale->total_amount, 2) }}</p>
                        <span class="bg-white text-green-600 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <h4 class="font-semibold text-gray-700 mb-3">Items Purchased:</h4>
                
                @if($sale->details && $sale->details->count() > 0)
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 px-3">Product</th>
                                <th class="text-center py-2 px-3">Quantity</th>
                                <th class="text-right py-2 px-3">Price</th>
                                <th class="text-right py-2 px-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->details as $detail)
                                @php
                                    $subtotal = ($detail->Quantity ?? 0) * ($detail->unit_price ?? 0);
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-3">{{ $detail->product->Product_Name ?? 'N/A' }}</td>
                                    <td class="text-center py-2 px-3">{{ $detail->Quantity ?? 0 }}</td>
                                    <td class="text-right py-2 px-3">₱{{ number_format($detail->unit_price ?? 0, 2) }}</td>
                                    <td class="text-right py-2 px-3 font-semibold">₱{{ number_format($subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-bold">
                                <td colspan="3" class="py-2 px-3 text-right">Total:</td>
                                <td class="text-right py-2 px-3">₱{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-gray-500 italic">No items found for this transaction</p>
                @endif

                <div class="mt-4 pt-4 border-t text-sm text-gray-600">
                    <p><strong>Payment Method:</strong> {{ ucfirst($sale->payment_method ?? 'N/A') }}</p>
                    <p><strong>Cashier:</strong> {{ $sale->user->full_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500 text-lg">This customer hasn't made any purchases yet.</p>
    </div>
@endif

@endsection