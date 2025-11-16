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
            <td class="py-2 px-4">₱{{ number_format($transaction->total_amount, 2) }}</td>
            <td class="py-2 px-4">
                <span class="px-2 py-1 rounded {{ $transaction->status=='paid'?'bg-green-600 text-white':'bg-yellow-400 text-black' }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </td>
            <td class="py-2 px-4">
                <div class="flex gap-2 items-center">
                    @if($transaction->status == 'pending')
                    <form action="{{ route('sales.markPaid', $transaction->transaction_ID) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 text-sm">
                            Mark as Paid & Print
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('sales.edit', $transaction->transaction_ID) }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Edit Status">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>

                    <form action="{{ route('sales.destroy', $transaction->transaction_ID) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white p-2 rounded hover:bg-red-700" onclick="return confirm('Are you sure?')" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection