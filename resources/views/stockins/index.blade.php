@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Stock-In Management</h1>

<a href="{{ route('stockins.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Add Stock</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-green-700 text-white">
                <th class="py-2 px-4">Stock ID</th>
                <th class="py-2 px-4">Product</th>
                <th class="py-2 px-4">Variety</th>
                <th class="py-2 px-4">Date</th>
                <th class="py-2 px-4">Quantity</th>
                <th class="py-2 px-4">Price</th>
                <th class="py-2 px-4">Unit</th>
                <th class="py-2 px-4">Expiry Date</th>
                <th class="py-2 px-4">Critical Level</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockIns as $stock)
            <tr class="border-t hover:bg-gray-50">
                <td class="py-2 px-4">{{ $stock->Stock_ID }}</td>
                <td class="py-2 px-4 font-medium">{{ $stock->product->Product_Name }}</td>
                <td class="py-2 px-4">
                    @if($stock->product->variety)
                        <span class="text-gray-700">{{ $stock->product->variety }}</span>
                    @else
                        <span class="text-gray-400 text-sm">N/A</span>
                    @endif
                </td>
                <td class="py-2 px-4">{{ $stock->date->format('M d, Y') }}</td>
                <td class="py-2 px-4">
                    <span class="font-semibold {{ $stock->quantity <= $stock->critical_level ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $stock->quantity }}
                    </span>
                </td>
                <td class="py-2 px-4">₱{{ number_format($stock->price, 2) }}</td>
                <td class="py-2 px-4">{{ $stock->unit }}</td>
                <td class="py-2 px-4">
                    @if($stock->expiry_date)
                        @php
                            $daysLeft = \Carbon\Carbon::now()->startOfDay()->diffInDays($stock->expiry_date->startOfDay(), false);
                            $isExpiringSoon = $daysLeft > 0 && $daysLeft <= 7;
                            $isExpired = $daysLeft <= 0;
                        @endphp
                        
                        @if($isExpired)
                            <span class="text-red-600 font-bold">Expired</span>
                        @elseif($isExpiringSoon)
                            <span class="text-orange-600 font-semibold">
                                {{ $stock->expiry_date->format('M d, Y') }}
                                <span class="block text-xs">({{ abs($daysLeft) }} days)</span>
                            </span>
                        @else
                            <span class="text-gray-600">{{ $stock->expiry_date->format('M d, Y') }}</span>
                        @endif
                    @else
                        <span class="text-gray-400 text-sm">N/A</span>
                    @endif
                </td>
                <td class="py-2 px-4">{{ $stock->critical_level }}</td>
                <td class="py-2 px-4">
                    <div class="flex gap-2">
                        <a href="{{ route('stockins.edit', $stock->Stock_ID) }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form action="{{ route('stockins.destroy', $stock->Stock_ID) }}" method="POST" class="inline">
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
            @empty
            <tr>
                <td colspan="10" class="py-4 px-4 text-center text-gray-500">No stock records found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection