@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow-lg">

    <h1 class="text-3xl font-bold text-green-700 mb-6">Edit Sale</h1>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('sales.update', $sale->transaction_ID) }}" method="POST" class="space-y-4" id="saleForm">
        @csrf
        @method('PUT')

        <!-- Customer Selection -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Customer</label>
            <select name="Customer_ID" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-green-500" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->Customer_ID }}" {{ $sale->Customer_ID == $customer->Customer_ID ? 'selected' : '' }}>
                        {{ $customer->Customer_Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Cashier Selection -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Cashier</label>
            <select name="User_ID" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-green-500" required>
                <option value="">Select Cashier</option>
                @foreach($users as $user)
                    <option value="{{ $user->User_ID }}" {{ $sale->User_ID == $user->User_ID ? 'selected' : '' }}>
                        {{ $user->fname }} {{ $user->lname }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Payment Method -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Payment Method</label>
            <select name="payment_method" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-green-500" required>
                <option value="">Select Payment Method</option>
                <option value="Cash" {{ $sale->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="GCash" {{ $sale->payment_method == 'GCash' ? 'selected' : '' }}>GCash</option>
            </select>
        </div>

        <!-- Products Section -->
        <h2 class="text-xl font-bold text-gray-800 mt-4 mb-2">Products</h2>
        <div id="products-wrapper" class="space-y-2">
            @foreach($sale->details as $index => $detail)
            <div class="flex gap-2 items-center product-row">
                <select name="products[{{ $index }}][Product_ID]" class="flex-1 border p-2 rounded focus:ring-2 focus:ring-green-500 product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        @php
                            $isExpired = false;
                            if($product->expiry_date) {
                                $expiryDate = \Carbon\Carbon::parse($product->expiry_date);
                                $daysLeft = (int) \Carbon\Carbon::now()->diffInDays($expiryDate, false);
                                $isExpired = $daysLeft <= 0;
                            }
                            $outOfStock = $product->Quantity_in_Stock <= 0;
                            $varietyText = $product->variety ? ' - ' . $product->variety : '';
                        @endphp
                        <option value="{{ $product->Product_ID }}" 
                            data-price="{{ $product->unit_price }}"
                            data-stock="{{ $product->Quantity_in_Stock }}"
                            data-name="{{ $product->Product_Name }}{{ $varietyText }}"
                            {{ $detail->Product_ID == $product->Product_ID ? 'selected' : '' }}
                            @if($isExpired || $outOfStock) disabled @endif>
                            {{ $product->Product_Name }}{{ $varietyText }} (Stock: {{ $product->Quantity_in_Stock }})
                            @if($isExpired) - EXPIRED @endif
                            @if($outOfStock) - OUT OF STOCK @endif
                        </option>
                    @endforeach
                </select>

                <!-- Quantity (Stock Deduction) -->
                <input type="number" name="products[{{ $index }}][Quantity]" value="{{ $detail->Quantity }}" placeholder="Quantity" class="w-24 border p-2 rounded focus:ring-2 focus:ring-green-500 quantity-input" min="0.1" step="0.1" required>

                <!-- Kilo (For Pricing) -->
                <input type="number" name="products[{{ $index }}][Kilo]" value="{{ $detail->Kilo ?? $detail->Quantity }}" placeholder="Kilo" class="w-24 border p-2 rounded focus:ring-2 focus:ring-green-500 kilo-input" min="0.1" step="0.1" required>

                <!-- Price per Kilo -->
                <input type="number" name="products[{{ $index }}][Price]" value="{{ $detail->unit_price }}" placeholder="Price per kg" class="w-28 border p-2 rounded focus:ring-2 focus:ring-green-500 price-input" min="0" step="0.01" required>

                <!-- Total -->
                <span class="w-24 text-gray-700 font-semibold total-display">{{ number_format(($detail->Kilo ?? $detail->Quantity) * $detail->unit_price, 2) }}</span>

                <!-- Remove -->
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded remove-btn">Remove</button>
            </div>
            @endforeach
        </div>

        <button type="button" id="addProductBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition mt-2">
            + Add Another Product
        </button>

        <!-- Grand Total -->
        <div class="flex justify-end mt-4">
            <span class="text-lg font-bold text-gray-800">Grand Total: ₱<span id="grandTotal">0.00</span></span>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-2">
            <button type="submit" id="submitBtn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
                Update Sale
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('products-wrapper');
    const addBtn = document.getElementById('addProductBtn');
    const grandTotalSpan = document.getElementById('grandTotal');
    const saleForm = document.getElementById('saleForm');

    let productIndex = {{ $sale->details->count() }};

    function updateTotals() {
        let grandTotal = 0;
        wrapper.querySelectorAll('.product-row').forEach(row => {
            const kilo = parseFloat(row.querySelector('.kilo-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = kilo * price;
            row.querySelector('.total-display').textContent = total.toFixed(2);
            grandTotal += total;
        });
        grandTotalSpan.textContent = grandTotal.toFixed(2);
    }

    function validateStock(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        
        const selectedOption = select.selectedOptions[0];
        if (!selectedOption || !selectedOption.value) return true;
        
        const maxStock = parseFloat(selectedOption.dataset.stock) || 0;
        const productName = selectedOption.dataset.name || 'Product';
        const enteredQuantity = parseFloat(quantityInput.value) || 0;
        
        // Check quantity against available stock
        if (enteredQuantity > maxStock) {
            quantityInput.classList.add('border-red-500', 'bg-red-50');
            showStockWarning(row, `${productName}: Quantity (${enteredQuantity}) exceeds available stock (${maxStock})`);
            return false;
        } else {
            quantityInput.classList.remove('border-red-500', 'bg-red-50');
        }
        
        hideStockWarning(row);
        return true;
    }

    function showStockWarning(row, message) {
        let warning = row.querySelector('.stock-warning');
        if (!warning) {
            warning = document.createElement('div');
            warning.className = 'stock-warning col-span-full text-red-600 text-sm font-semibold mt-1';
            row.appendChild(warning);
        }
        warning.textContent = '⚠ ' + message;
    }

    function hideStockWarning(row) {
        const warning = row.querySelector('.stock-warning');
        if (warning) {
            warning.remove();
        }
    }

    function validateAllRows() {
        let allValid = true;
        wrapper.querySelectorAll('.product-row').forEach(row => {
            if (!validateStock(row)) {
                allValid = false;
            }
        });
        return allValid;
    }

    // Initial total calculation
    updateTotals();

    // Update totals when kilo, quantity, or price changes
    wrapper.addEventListener('input', (e) => {
        const row = e.target.closest('.product-row');
        
        if(e.target.classList.contains('quantity-input')) {
            validateStock(row);
        }
        
        if(e.target.classList.contains('kilo-input') || e.target.classList.contains('price-input') || e.target.classList.contains('quantity-input')) {
            updateTotals();
        }
    });

    // Auto-fill price and validate when product changes
    wrapper.addEventListener('change', (e) => {
        if(e.target.classList.contains('product-select')) {
            const row = e.target.closest('.product-row');
            const selectedOption = e.target.selectedOptions[0];
            const priceInput = row.querySelector('.price-input');
            const quantityInput = row.querySelector('.quantity-input');
            
            if(selectedOption && selectedOption.dataset.price) {
                priceInput.value = selectedOption.dataset.price;
                
                // Set max attribute for quantity
                const maxStock = parseFloat(selectedOption.dataset.stock) || 0;
                quantityInput.setAttribute('max', maxStock);
                
                updateTotals();
            }
            
            validateStock(row);
        }
    });

    // Add new product row
    addBtn.addEventListener('click', () => {
        const newRow = wrapper.querySelector('.product-row').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.classList.remove('border-red-500', 'bg-red-50');
        });
        newRow.querySelector('select').value = '';
        newRow.querySelector('.total-display').textContent = '0.00';
        
        // Update name attributes with new index
        const inputs = newRow.querySelectorAll('input, select');
        inputs.forEach(input => {
            if(input.name) {
                input.name = input.name.replace(/\[\d+\]/, '[' + productIndex + ']');
            }
        });
        
        // Remove any existing warnings
        const warning = newRow.querySelector('.stock-warning');
        if (warning) warning.remove();
        
        wrapper.appendChild(newRow);
        productIndex++;
    });

    // Remove product row
    wrapper.addEventListener('click', (e) => {
        if(e.target.classList.contains('remove-btn')) {
            if(wrapper.querySelectorAll('.product-row').length > 1) {
                e.target.closest('.product-row').remove();
                updateTotals();
            } else {
                alert('You must have at least one product in the sale.');
            }
        }
    });

    // Form submission validation
    saleForm.addEventListener('submit', (e) => {
        if (!validateAllRows()) {
            e.preventDefault();
            alert('Please fix the stock quantity errors before submitting.');
            return false;
        }
    });
});
</script>
@endsection