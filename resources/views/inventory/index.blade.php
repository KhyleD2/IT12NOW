@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Inventory</h1>

<a href="{{ route('inventory.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Add Product</a>


@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-green-700 text-white">
                <th class="py-2 px-4">ID</th>
                <th class="py-2 px-4">Product Name</th>
                <th class="py-2 px-4">Category</th>
                <th class="py-2 px-4">Stock</th>
                <th class="py-2 px-4">Unit Price</th>
                <th class="py-2 px-4">Expiry Date</th>
                <th class="py-2 px-4">Supplier</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-t hover:bg-gray-50">
                <td class="py-2 px-4">{{ $product->Product_ID }}</td>
                <td class="py-2 px-4 font-medium">{{ $product->Product_Name }}</td>
                <td class="py-2 px-4">{{ $product->Category }}</td>
                <td class="py-2 px-4">
                    <span class="font-semibold {{ $product->Quantity_in_Stock <= 10 ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $product->Quantity_in_Stock }}
                    </span>
                </td>
                <td class="py-2 px-4">₱{{ number_format($product->unit_price, 2) }}</td>
                <td class="py-2 px-4">
    @if($product->expiry_date)
    @php
        $expiryDate = \Carbon\Carbon::parse($product->expiry_date);
        $daysLeft = $product->days_until_expiry ?? \Carbon\Carbon::now()->diffInDays($expiryDate, false);
        $isExpiringSoon = $daysLeft > 0 && $daysLeft <= 7;
        $isExpired = $daysLeft <= 0;
    @endphp
        
        @if($isExpired)
            <span class="text-red-600 font-bold">
                Expired
            </span>
        @elseif($isExpiringSoon)
            <span class="text-orange-600 font-semibold">
                {{ $expiryDate->format('M d, Y') }}
                <span class="block text-xs">({{ abs($daysLeft) }} days)</span>
            </span>
        @else
            <span class="text-gray-600">
                {{ $expiryDate->format('M d, Y') }}
            </span>
        @endif
    @else
        <span class="text-gray-400 text-sm">N/A</span>
    @endif
</td>
                <td class="py-2 px-4">{{ $product->supplier->Supplier_Name }}</td>
                <td class="py-2 px-4">
                    <div class="flex gap-2">
                        <a href="{{ route('inventory.edit', $product->Product_ID) }}" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('inventory.destroy', $product->Product_ID) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection