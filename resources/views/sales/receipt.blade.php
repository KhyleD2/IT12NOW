@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow-lg">
    <h1 class="text-2xl font-bold mb-4 text-green-700 text-center">Receipt</h1>

    <div class="mb-4">
        <p><strong>Receipt Number:</strong> {{ $sale->receipt_number }}</p>
        <p><strong>Customer:</strong> {{ $sale->customer->Customer_Name }}</p>
        <p><strong>Cashier:</strong> {{ $sale->user->fname }} {{ $sale->user->lname }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->transaction_date)->format('M d, Y h:i A') }}</p>
        <p><strong>Payment Method:</strong> {{ $sale->payment_method }}</p>
    </div>

    <hr class="my-2">

    <table class="w-full text-left mb-4">
        <thead>
            <tr class="border-b">
                <th class="py-1">Product</th>
                <th class="py-1 text-right">Qty</th>
                <th class="py-1 text-right">Price</th>
                <th class="py-1 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->details as $detail)
            <tr class="border-b">
                <td class="py-2">
                    {{ $detail->product->Product_Name }}
                    @if($detail->product->variety)
                        <br><small class="text-gray-600">{{ $detail->product->variety }}</small>
                    @endif
                </td>
                <td class="py-2 text-right">{{ $detail->Quantity }} kg</td>
                <td class="py-2 text-right">₱{{ number_format($detail->unit_price, 2) }}</td>
                <td class="py-2 text-right">₱{{ number_format($detail->Quantity * $detail->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="my-2 border-t-2 border-black">
    <div class="text-right">
        <p class="font-bold text-lg">Grand Total: ₱{{ number_format($sale->total_amount, 2) }}</p>
        <p class="text-sm text-gray-600 mt-1">Status: <span class="font-semibold {{ $sale->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($sale->status) }}</span></p>
    </div>

    <div class="flex justify-center mt-6 gap-2 print:hidden">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Print Receipt</button>
        <a href="{{ route('sales.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition inline-block">Back to Sales</a>
    </div>

    <div class="text-center mt-4 text-sm text-gray-500">
        <p>Thank you for your purchase!</p>
        <p>CRM FruitStand</p>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .max-w-md, .max-w-md * {
            visibility: visible;
        }
        .max-w-md {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection