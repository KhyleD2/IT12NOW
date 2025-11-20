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
                            // Check if product is expired using same logic as inventory
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
                            @if($isExpired || $outOfStock) disabled @endif
                        >
                            {{ $product->Product_Name }}{{ $varietyText }} (Stock: {{ $product->Quantity_in_Stock }})
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
            <button type="submit" id="submitBtn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
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
    const saleForm = document.getElementById('saleForm');
    const submitBtn = document.getElementById('submitBtn');

    // Store max stock per row
    const rowStockLimits = new Map();

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

    function validateStock(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity');
        const kiloInput = row.querySelector('.kilo');
        
        const selectedOption = select.selectedOptions[0];
        if (!selectedOption || !selectedOption.value) return true;
        
        const maxStock = parseFloat(selectedOption.dataset.stock) || 0;
        const productName = selectedOption.dataset.name || 'Product';
        const enteredQuantity = parseFloat(quantityInput.value) || 0;
        const enteredKilo = parseFloat(kiloInput.value) || 0;
        
        // Check both quantity and kilo against stock
        if (enteredQuantity > maxStock) {
            quantityInput.classList.add('border-red-500', 'bg-red-50');
            showStockWarning(row, `${productName}: Quantity (${enteredQuantity}) exceeds available stock (${maxStock})`);
            return false;
        } else {
            quantityInput.classList.remove('border-red-500', 'bg-red-50');
        }
        
        if (enteredKilo > maxStock) {
            kiloInput.classList.add('border-red-500', 'bg-red-50');
            showStockWarning(row, `${productName}: Kilo (${enteredKilo}) exceeds available stock (${maxStock})`);
            return false;
        } else {
            kiloInput.classList.remove('border-red-500', 'bg-red-50');
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

    // Auto-fill price and set max stock when product is selected
    wrapper.addEventListener('change', e => {
        if(e.target.classList.contains('product-select')) {
            const row = e.target.closest('.product-row');
            const selected = e.target.selectedOptions[0];
            const priceInput = row.querySelector('.price');
            const quantityInput = row.querySelector('.quantity');
            const kiloInput = row.querySelector('.kilo');
            
            if(selected && selected.dataset.price) {
                priceInput.value = selected.dataset.price;
                
                // Set max attribute based on stock
                const maxStock = parseFloat(selected.dataset.stock) || 0;
                quantityInput.setAttribute('max', maxStock);
                kiloInput.setAttribute('max', maxStock);
                
                updateTotals();
            }
            
            validateStock(row);
        }
    });

    // Validate stock on input change
    wrapper.addEventListener('input', e => {
        const row = e.target.closest('.product-row');
        
        if(e.target.classList.contains('quantity') || e.target.classList.contains('kilo')) {
            validateStock(row);
        }
        
        if(e.target.classList.contains('kilo') || e.target.classList.contains('price')) {
            updateTotals();
        }
    });

    // Remove product row
    wrapper.addEventListener('click', e => {
        if(e.target.classList.contains('remove-btn')) {
            if(wrapper.querySelectorAll('.product-row').length > 1) {
                e.target.closest('.product-row').remove();
                updateTotals();
            } else {
                alert('You must have at least one product.');
            }
        }
    });

    // Add new product row
    let rowIndex = 1;
    addBtn.addEventListener('click', () => {
        const newRow = wrapper.querySelector('.product-row').cloneNode(true);
        
        // Clear values
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.classList.remove('border-red-500', 'bg-red-50');
        });
        newRow.querySelector('select').value = '';
        newRow.querySelector('.total').textContent = '0.00';
        
        // Update name attributes
        newRow.querySelectorAll('[name]').forEach(field => {
            field.name = field.name.replace(/\[0\]/, `[${rowIndex}]`);
        });
        
        // Remove any existing warnings
        const warning = newRow.querySelector('.stock-warning');
        if (warning) warning.remove();
        
        wrapper.appendChild(newRow);
        rowIndex++;
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