@extends('layouts.app')

@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-3">
        <h1 class="text-3xl font-bold text-gray-800">Supplier Transactions</h1>

        <a href="{{ route('supplier-transactions.create') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700
                  transition duration-200 text-center">
            + Add Transaction
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-900 border border-green-300 p-4 rounded-lg mb-5">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto bg-white shadow-lg rounded-xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Supplier</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Product</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Qty (Units)</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Qty (Kilos)</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Supply Date</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Total Cost</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($transactions as $t)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">{{ $t->supplier->Supplier_Name }}</td>
                        <td class="px-4 py-3">{{ $t->product->Product_Name }}</td>
                        <td class="px-4 py-3">{{ $t->quantity_units }}</td>
                        <td class="px-4 py-3">{{ $t->quantity_kilos }}</td>
                        <td class="px-4 py-3">{{ $t->supply_date }}</td>
                        <td class="px-4 py-3 font-semibold">₱{{ number_format($t->total_cost, 2) }}</td>

                        <!-- Status Badges -->
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'pending' => 'text-yellow-800 bg-yellow-200',
                                    'completed' => 'text-green-800 bg-green-200',
                                    'cancelled' => 'text-red-800 bg-red-200',
                                    'paid' => 'text-blue-800 bg-blue-200',
                                ];
                            @endphp

                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$t->status] ?? '' }}">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-3 flex justify-center space-x-3">

                            <!-- Edit -->
                            <a href="{{ route('supplier-transactions.edit', $t->Supply_transac_ID) }}"
                               class="text-blue-600 hover:text-blue-900 text-xl"
                               title="Edit">
                                ✏️
                            </a>

                            <!-- Delete -->
                            <form action="{{ route('supplier-transactions.destroy', $t->Supply_transac_ID) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this transaction?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-900 text-xl"
                                        title="Delete">
                                    🗑️
                                </button>
                            </form>

                            <!-- Mark as Paid -->
                            @if($t->status == 'pending')
                                <form action="{{ route('supplier-transactions.pay', $t->Supply_transac_ID) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="text-green-600 hover:text-green-900 text-xl"
                                            title="Mark as Paid">
                                        ✅
                                    </button>
                                </form>
                            @endif

                            <!-- Receipt -->
                            <a href="{{ route('supplier-transactions.receipt', $t->Supply_transac_ID) }}"
                               class="text-purple-600 hover:text-purple-900 text-xl"
                               title="View Receipt">
                                🧾
                            </a>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500 italic">
                            No transactions found.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>
@endsection
