@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow-lg">
    <h1 class="text-2xl font-bold mb-4 text-green-700">Receipt</h1>

    <p><strong>Receipt Number:</strong> {{ $sale->receipt_number }}</p>
    <p><strong>Customer:</strong> {{ $sale->customer->Customer_Name }}</p>
    <p><strong>Cashier:</strong> {{ $sale->user->fname }} {{ $sale->user->lname }}</p>
    <p><strong>Date:</strong> {{ $sale->transaction_date }}</p>

    <hr class="my-2">

    <table class="w-full text-left mb-4">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->details as $detail)
            <tr>
                <td>{{ $detail->product->Product_Name }}</td>
                <td>{{ $detail->Quantity }}</td>
                <td>₱{{ number_format($detail->unit_price, 2) }}</td>
                <td>₱{{ number_format($detail->Quantity * $detail->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="my-2">
    <p class="text-right font-bold text-lg">Grand Total: ₱{{ number_format($sale->total_amount, 2) }}</p>

    <div class="flex justify-center mt-4 gap-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
        <a href="{{ route('sales.index') }}" class="bg-green-600 text-white px-4 py-2 rounded">Back</a>
    </div>
</div>
@endsection
