@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Customers</h1>

<a href="{{ route('customers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Add Customer</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<table class="min-w-full bg-white rounded shadow">
    <thead>
        <tr class="bg-green-700 text-white">
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Customer Name</th>
            <th class="py-2 px-4">Contact Number</th>
            <th class="py-2 px-4">Latest Purchase</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr class="border-t">
            <td class="py-2 px-4">{{ $customer->Customer_ID }}</td>
            <td class="py-2 px-4">{{ $customer->Customer_Name }}</td>
            <td class="py-2 px-4">{{ $customer->Contact_Number }}</td>
            <td class="py-2 px-4">
                @if($customer->latestSale)
                    Receipt: {{ $customer->latestSale->receipt_number }} <br>
                    Total: ₱{{ number_format($customer->latestSale->total_amount, 2) }} <br>
                    Status: <span class="{{ $customer->latestSale->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ ucfirst($customer->latestSale->status) }}
                    </span>
                @else
                    -
                @endif
            </td>
            <td class="py-2 px-4 flex gap-2">
                <a href="{{ route('customers.edit', $customer->Customer_ID) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                <form action="{{ route('customers.destroy', $customer->Customer_ID) }}" method="POST">
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
