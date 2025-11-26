@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Sales Transactions</h1>

<a href="{{ route('sales.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">Add Sale</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-green-700 text-white">
                <th class="py-3 px-4 text-left">ID</th>
                <th class="py-3 px-4 text-left">Customer</th>
                <th class="py-3 px-4 text-left">Cashier</th>
                <th class="py-3 px-4 text-left">Date</th>
                <th class="py-3 px-4 text-right">Total Amount</th>
                <th class="py-3 px-4 text-center">Payment</th>
                <th class="py-3 px-4 text-center">Status</th>
                <th class="py-3 px-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr class="border-t hover:bg-gray-50">
                <td class="py-3 px-4">{{ $transaction->transaction_ID }}</td>
                <td class="py-3 px-4">{{ $transaction->customer->Customer_Name ?? 'N/A' }}</td>
                <td class="py-3 px-4">{{ $transaction->user ? ($transaction->user->fname . ' ' . $transaction->user->lname) : 'N/A' }}</td>
                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</td>
                <td class="py-3 px-4 text-right font-semibold">₱{{ number_format($transaction->total_amount, 2) }}</td>
                <td class="py-3 px-4 text-center">
                    <span class="text-sm text-gray-600">{{ $transaction->payment_method }}</span>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="px-3 py-1 rounded text-sm font-medium {{ $transaction->status=='paid'?'bg-green-600 text-white':'bg-yellow-400 text-black' }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <div class="flex gap-2 items-center justify-center">
                        <!-- View Details Button -->
                        <button onclick="viewTransaction({{ $transaction->transaction_ID }})" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700" title="View Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>

                        @if($transaction->status == 'pending')
                        <form action="{{ route('sales.markPaid', $transaction->transaction_ID) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 text-xs whitespace-nowrap">
                                Mark Paid
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('sales.edit', $transaction->transaction_ID) }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Edit">
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
            @empty
            <tr>
                <td colspan="8" class="py-8 text-center text-gray-500">No transactions found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal for Transaction Details -->
<div id="transactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-green-700 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
            <h2 class="text-2xl font-bold">Transaction Details</h2>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
function viewTransaction(transactionId) {
    document.getElementById('transactionModal').classList.remove('hidden');
    
    fetch(`/sales/${transactionId}/details`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded">
                        <div>
                            <p class="text-sm text-gray-600">Transaction ID</p>
                            <p class="font-semibold">#${data.transaction_ID}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Receipt Number</p>
                            <p class="font-semibold">${data.receipt_number}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Customer</p>
                            <p class="font-semibold">${data.customer.Customer_Name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Cashier</p>
                            <p class="font-semibold">${data.user.fname} ${data.user.lname}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date</p>
                            <p class="font-semibold">${new Date(data.transaction_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment Method</p>
                            <p class="font-semibold">${data.payment_method}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Products</h3>
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-3 text-left text-sm">Product</th>
                                    <th class="py-2 px-3 text-center text-sm">Qty</th>
                                    <th class="py-2 px-3 text-right text-sm">Unit Price</th>
                                    <th class="py-2 px-3 text-right text-sm">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.details.map(detail => `
                                    <tr class="border-t">
                                        <td class="py-2 px-3">${detail.product.Product_Name}</td>
                                        <td class="py-2 px-3 text-center">${detail.Quantity}</td>
                                        <td class="py-2 px-3 text-right">₱${parseFloat(detail.unit_price).toFixed(2)}</td>
                                        <td class="py-2 px-3 text-right font-semibold">₱${(detail.Quantity * detail.unit_price).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr class="border-t-2 border-gray-300">
                                    <td colspan="3" class="py-3 px-3 text-right font-bold text-lg">Total Amount:</td>
                                    <td class="py-3 px-3 text-right font-bold text-lg text-green-700">₱${parseFloat(data.total_amount).toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Close
                        </button>
                    </div>
                </div>
            `;
            document.getElementById('modalContent').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = `
                <div class="text-red-600 text-center py-8">
                    <p>Error loading transaction details.</p>
                    <button onclick="closeModal()" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

document.getElementById('transactionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection