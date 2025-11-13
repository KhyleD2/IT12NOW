@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Sales Transactions</h1>

<a href="{{ route('sales.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Add Sale</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<table class="min-w-full bg-white rounded shadow">
    <thead>
        <tr class="bg-green-700 text-white">
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Customer</th>
            <th class="py-2 px-4">Cashier</th>
            <th class="py-2 px-4">Total Amount</th>
            <th class="py-2 px-4">Status</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
        <tr class="border-t">
            <td class="py-2 px-4">{{ $transaction->transaction_ID }}</td>
            <td class="py-2 px-4">{{ $transaction->customer->Customer_Name }}</td>
            <td class="py-2 px-4">{{ $transaction->user->fname }}</td>
            <td class="py-2 px-4">{{ number_format($transaction->total_amount,2) }}</td>
            <td class="py-2 px-4">
                <span class="px-2 py-1 rounded {{ $transaction->status=='paid'?'bg-green-600 text-white':'bg-yellow-400 text-black' }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </td>
            <td class="py-2 px-4 flex gap-2">
               @if($transaction->status == 'pending')
<form action="{{ route('sales.markPaid', $transaction->transaction_ID) }}" method="POST">
    @csrf
    @method('PUT')
    <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">
        Mark as Paid & Print
    </button>
</form>
@endif


                <a href="{{ route('sales.edit', $transaction->transaction_ID) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit Status</a>

                <form action="{{ route('sales.destroy', $transaction->transaction_ID) }}" method="POST">
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
