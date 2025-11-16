@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Customers</h1>

<a href="{{ route('customers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Add Customer</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="bg-green-700 text-white">
                <th class="py-3 px-4 text-left">Customer Name</th>
                <th class="py-3 px-4 text-left">Contact Number</th>
                <th class="py-3 px-4 text-left">Receipt</th>
                <th class="py-3 px-4 text-left">Total</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                @if($customer->sales && $customer->sales->count() > 0)
                    @foreach($customer->sales as $sale)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $customer->Customer_Name }}</td>
                            <td class="py-3 px-4">{{ $customer->Contact_Number }}</td>
                            <td class="py-3 px-4">{{ $sale->receipt_number }}</td>
                            <td class="py-3 px-4">₱{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="py-3 px-4">
                                <span class="{{ $sale->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }} font-semibold">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('customers.show', $customer->Customer_ID) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer->Customer_ID) }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer->Customer_ID) }}" method="POST" class="inline">
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
                @else
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $customer->Customer_Name }}</td>
                        <td class="py-3 px-4">{{ $customer->Contact_Number }}</td>
                        <td class="py-3 px-4 text-gray-400 italic" colspan="3">No purchases yet</td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <a href="{{ route('customers.show', $customer->Customer_ID) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('customers.edit', $customer->Customer_ID) }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->Customer_ID) }}" method="POST" class="inline">
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
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection