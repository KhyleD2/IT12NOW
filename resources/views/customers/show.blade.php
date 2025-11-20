@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-800 font-semibold transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Customers
    </a>
</div>

<!-- Customer Header Card -->
<div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-8 mb-6 text-white">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-green-100 text-sm font-semibold uppercase tracking-wider">Customer Profile</p>
            <h1 class="text-4xl font-bold mt-2">{{ $customer->Customer_Name }}</h1>
            <p class="text-green-100 mt-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ $customer->Contact_Number }}
            </p>
        </div>
        <div class="bg-white bg-opacity-20 p-4 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
    </div>
</div>

<!-- Purchase Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Total Purchases</p>
                <p class="text-4xl font-bold text-gray-800 mt-3">{{ $customer->sales->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">transactions</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Total Spent</p>
                <p class="text-4xl font-bold text-gray-800 mt-3">₱{{ number_format($customer->sales->sum('total_amount'), 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">all time</p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Average Purchase</p>
                <p class="text-4xl font-bold text-gray-800 mt-3">₱{{ number_format($customer->sales->avg('total_amount'), 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">per transaction</p>
            </div>
            <div class="bg-purple-100 p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Purchase History Section -->
<div class="mb-6">
    <div class="flex items-center gap-2 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h2 class="text-2xl font-bold text-gray-800">Purchase History</h2>
    </div>

    @if($customer->sales && $customer->sales->count() > 0)
        <div class="space-y-4">
            @foreach($customer->sales as $sale)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Transaction Header -->
                    <div class="bg-gradient-to-r from-green-600 to-green-700 p-5 text-white">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <p class="text-green-100 text-sm font-semibold">Receipt #</p>
                                <p class="text-lg font-bold">{{ $sale->receipt_number }}</p>
                                <p class="text-green-100 text-sm mt-1">
                                    {{ \Carbon\Carbon::parse($sale->transaction_date)->format('F d, Y • h:i A') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-green-100 text-sm font-semibold">Amount</p>
                                <p class="text-4xl font-bold">₱{{ number_format($sale->total_amount, 2) }}</p>
                                <span class="inline-block bg-white text-green-600 px-4 py-1 rounded-full text-xs font-bold mt-2">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="p-6">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Items Purchased
                        </h4>
                        
                        @if($sale->details && $sale->details->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b-2 border-gray-200 bg-gray-50">
                                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Product</th>
                                            <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Qty</th>
                                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Price</th>
                                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($sale->details as $detail)
                                            @php
                                                $subtotal = ($detail->Quantity ?? 0) * ($detail->unit_price ?? 0);
                                            @endphp
                                            <tr class="hover:bg-green-50 transition-colors">
                                                <td class="py-3 px-4 font-medium text-gray-800">{{ $detail->product->Product_Name ?? 'N/A' }}</td>
                                                <td class="text-center py-3 px-4 text-gray-700">{{ $detail->Quantity ?? 0 }}</td>
                                                <td class="text-right py-3 px-4 text-gray-700">₱{{ number_format($detail->unit_price ?? 0, 2) }}</td>
                                                <td class="text-right py-3 px-4 font-semibold text-gray-800">₱{{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-green-50 border-t-2 border-gray-200 font-bold">
                                            <td colspan="3" class="py-3 px-4 text-right text-gray-800">Total:</td>
                                            <td class="text-right py-3 px-4 text-green-700 text-lg">₱{{ number_format($sale->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No items found for this transaction</p>
                        @endif

                        <!-- Transaction Footer -->
                        <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 font-semibold">Payment Method</p>
                                <p class="text-gray-800 mt-1">{{ ucfirst($sale->payment_method ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-semibold">Cashier</p>
                                <p class="text-gray-800 mt-1">{{ $sale->user->fname ?? 'N/A' }} {{ $sale->user->lname ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-500 text-lg font-medium">No purchase history yet</p>
            <p class="text-gray-400 mt-2">This customer hasn't made any purchases</p>
        </div>
    @endif
</div>

@endsection