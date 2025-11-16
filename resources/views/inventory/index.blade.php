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
                <th class="py-2 px-4">Variety</th>
                <th class="py-2 px-4">Description</th>
                <th class="py-2 px-4">Image</th>
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
                <td class="py-2 px-4">{{ $product->variety ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $product->description ?? 'N/A' }}</td>
                <td class="py-2 px-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->Product_Name }}" class="w-16 h-16 object-cover rounded">
                    @else
                        <span class="text-gray-400 text-sm">N/A</span>
                    @endif
                </td>
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
        $daysLeft = (int) \Carbon\Carbon::now()->diffInDays($expiryDate, false);
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
                <span class="block text-xs">({{ $daysLeft }} days)</span>
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
                <td class="py-2 px-4">{{ $product->supplier ? $product->supplier->Supplier_Name : 'N/A' }}</td>
                <td class="py-2 px-4">
                    <div class="flex gap-2">
                        <a href="{{ route('inventory.edit', $product->Product_ID) }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form action="{{ route('inventory.destroy', $product->Product_ID) }}" method="POST" class="inline">
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
</div>
@endsection