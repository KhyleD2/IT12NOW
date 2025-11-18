@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow-lg">

    <h1 class="text-3xl font-bold text-green-700 mb-6">Add Sale</h1>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST" class="space-y-4" id="saleForm">
        @csrf

        <!-- Customer -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Customer</label>
            <select name="Customer_ID" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-green-500" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->Customer_ID }}">{{ $customer->Customer_Name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Cashier -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Cashier</label>
            <select name="User_ID" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-green-500" required>
                <option value="">Select Cashier</option>
                @foreach($users as $user)
                    <option value="{{ $user->User_ID }}">{{ $user->fname }} {{ $user->lname }}</option>
                @endforeach
            </select>
        </div>

        <!-- Payment Method -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Payment Method</label>
            <select name="payment_method" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-green-500" required>
                <option value="">Select Payment Method</option>
                <option value="Cash">Cash</option>
                <option value="GCash">GCash</option>
            </select>
        </div>

        <!-- Products Section -->
        <h2 class="text-xl font-bold text-gray-800 mt-4 mb-2">Products</h2>
        <div id="products-wrapper" class="space-y-2">
            <div class="flex gap-2 items-center product-row">
                <select name="products[0][Product_ID]" class="flex-1 border p-2 rounded focus:ring-2 focus:ring-green-500 product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        @php
                            $isExpired = $product->expiry_date && \Carbon\Carbon::parse($product->expiry_date)->lt(now());
                            $outOfStock = $product->Quantity_in_Stock <= 0;
                        @endphp
                        <option value="{{ $product->Product_ID }}"
                            data-price="{{ $product->unit_price }}"
                            @if($isExpired || $outOfStock) disabled @endif
                        >
                            {{ $product->Product_Name }} (Stock: {{ $product->Quantity_in_Stock }})
                            @if($isExpired) - EXPIRED @endif
                            @if($outOfStock) - OUT OF STOCK @endif
                        </option>
                    @endforeach
                </select>

                <input type="number" name="products[0][Quantity]" placeholder="Quantity" class="w-24 border p-2 rounded focus:ring-2 focus:ring-green-500 quantity" min="0.1" step="0.1" required>
                <input type="number" name="products[0][Kilo]" placeholder="Kilo" class="w-24 border p-2 rounded focus:ring-2 focus:ring-green-500 kilo" min="0.1" step="0.1" required>
                <input type="number" name="products[0][Price]" placeholder="Price per kg" class="w-24 border p-2 rounded focus:ring-2 focus:ring-green-500 price" min="0" step="0.01" required>
                <span class="w-24 text-gray-700 font-semibold total">0.00</span>
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded remove-btn">Remove</button>
            </div>
        </div>

        <button type="button" id="addProductBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition mt-2">
            + Add Another Product
        </button>

        <!-- Grand Total -->
        <div class="flex justify-end mt-4">
            <span class="text-lg font-bold text-gray-800">Grand Total: ₱<span id="grandTotal">0.00</span></span>
        </div>

        <!-- Submit -->
        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
                Save Sale
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('products-wrapper');
    const addBtn = document.getElementById('addProductBtn');
    const grandTotalSpan = document.getElementById('grandTotal');

    function updateTotals() {
        let grandTotal = 0;
        wrapper.querySelectorAll('.product-row').forEach(row => {
            const kilo = parseFloat(row.querySelector('.kilo').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const total = kilo * price;
            row.querySelector('.total').textContent = total.toFixed(2);
            grandTotal += total;
        });
        grandTotalSpan.textContent = grandTotal.toFixed(2);
    }

    // Auto-fill price from product selection
    wrapper.addEventListener('change', e => {
        if(e.target.classList.contains('product-select')) {
            const selected = e.target.selectedOptions[0];
            const priceInput = e.target.parentElement.querySelector('.price');
            if(selected && selected.dataset.price) {
                priceInput.value = selected.dataset.price;
                updateTotals();
            }
        }
    });

    // Update totals when kilo or price changes
    wrapper.addEventListener('input', e => {
        if(e.target.classList.contains('kilo') || e.target.classList.contains('price')) {
            updateTotals();
        }
    });

    // Remove product row
    wrapper.addEventListener('click', e => {
        if(e.target.classList.contains('remove-btn')) {
            if(wrapper.querySelectorAll('.product-row').length > 1) {
                e.target.parentElement.remove();
                updateTotals();
            }
        }
    });

    // Add new product row
    addBtn.addEventListener('click', () => {
        const newRow = wrapper.querySelector('.product-row').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('select').value = '';
        newRow.querySelector('.total').textContent = '0.00';
        wrapper.appendChild(newRow);
    });
});
</script>
@endsection
