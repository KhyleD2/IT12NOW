@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Add Stock</h1>

@if ($errors->any())
<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Info box for completed transactions -->
@if(!empty($latestSupplierTransactions))
<div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                <strong>Note:</strong> Select a product with completed supplier transactions to auto-fill quantity and price. Quantity is limited to what was supplied.
            </p>
        </div>
    </div>
</div>
@endif

<form action="{{ route('stockins.store') }}" method="POST" class="bg-white p-6 rounded shadow" id="stockin_form">
    @csrf
    
    <!-- Hidden field to store supplier transaction ID -->
    <input type="hidden" name="supplier_transaction_id" id="supplier_transaction_id" value="">
    
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Product</label>
        <select name="Product_ID" id="product_select" class="w-full border p-2 rounded" required>
            <option value="">-- Select Product --</option>
            @foreach($products as $product)
                <option value="{{ $product->Product_ID }}">
                    {{ $product->Product_Name }}@if($product->variety) - {{ $product->variety }}@endif ({{ $product->Category }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Date</label>
        <input type="date" name="date" id="date_input" value="{{ date('Y-m-d') }}" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
        <input type="number" step="0.01" name="quantity" id="quantity_input" class="w-full border p-2 rounded" min="0.01" required>
        <p id="quantity_note" class="text-sm text-blue-600 mt-1"></p>
        <p id="quantity_warning" class="text-sm text-red-600 mt-1 hidden"></p>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Price per Unit</label>
        <input type="number" step="0.01" name="price" id="price_input" class="w-full border p-2 rounded" min="0" required>
        <p id="price_note" class="text-sm text-blue-600 mt-1"></p>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Unit (e.g., kg, pcs, box)</label>
        <input type="text" name="unit" value="kg" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Expiry Date (Optional)</label>
        <input type="date" name="expiry_date" class="w-full border p-2 rounded" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Critical Level</label>
        <input type="number" name="critical_level" value="5" class="w-full border p-2 rounded" min="0" required>
    </div>

    <div class="flex gap-2">
        <button type="submit" id="submit_btn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Stock</button>
        <a href="{{ route('stockins.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
    </div>
</form>

<script>
const productSelect = document.getElementById('product_select');
const quantityInput = document.getElementById('quantity_input');
const priceInput = document.getElementById('price_input');
const dateInput = document.getElementById('date_input');
const quantityNote = document.getElementById('quantity_note');
const priceNote = document.getElementById('price_note');
const quantityWarning = document.getElementById('quantity_warning');
const submitBtn = document.getElementById('submit_btn');
const stockinForm = document.getElementById('stockin_form');
const supplierTransactionIdInput = document.getElementById('supplier_transaction_id');

// Latest supplier transactions data from controller
const latestTransactions = @json($latestSupplierTransactions);

// Store max quantity for current product
let maxQuantity = null;

// Debug: Check if data is received
console.log('Latest Completed Transactions with Remaining Qty:', latestTransactions);

function autoFillFromSupplierTransaction() {
    const productId = productSelect.value;
    
    // Reset notes and warnings
    quantityNote.innerHTML = '';
    priceNote.innerHTML = '';
    quantityWarning.classList.add('hidden');
    quantityWarning.innerHTML = '';
    maxQuantity = null;
    
    // Clear the quantity input when changing products
    quantityInput.value = '';
    
    // Remove max attribute and clear supplier transaction ID
    quantityInput.removeAttribute('max');
    supplierTransactionIdInput.value = '';
    
    if (productId && latestTransactions[productId]) {
        const transaction = latestTransactions[productId];
        
        // Set max quantity limit (REMAINING quantity, not original)
        maxQuantity = parseFloat(transaction.quantity);
        quantityInput.setAttribute('max', maxQuantity);
        
        // Store the supplier transaction ID
        supplierTransactionIdInput.value = transaction.transaction_id;
        
        // Auto-fill quantity with REMAINING quantity
        quantityInput.value = transaction.quantity;
        quantityNote.innerHTML = '<span class="text-orange-600">(Remaining: ' + maxQuantity + ' kg of ' + transaction.original_quantity + ' kg supplied)</span>';
        
        // Auto-fill price (calculated per kg)
        priceInput.value = transaction.price;
        priceNote.innerHTML = '';
        
    } else {
        // Clear fields if no completed transaction found
        quantityInput.value = '';
        priceInput.value = '';
        
        if (productId) {
            quantityNote.innerHTML = '<span class="text-gray-500">No available supplier transaction for this product.</span>';
            priceNote.innerHTML = '<span class="text-gray-500">Please enter price manually.</span>';
        }
    }
}

// Validate quantity on input
quantityInput.addEventListener('input', function() {
    if (maxQuantity !== null) {
        const enteredQuantity = parseFloat(this.value);
        
        if (enteredQuantity > maxQuantity) {
            quantityWarning.classList.remove('hidden');
            quantityWarning.innerHTML = '<strong>Warning:</strong> Quantity cannot exceed ' + maxQuantity + ' kg (remaining quantity). Please adjust.';
            quantityInput.classList.add('border-red-500');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            quantityWarning.classList.add('hidden');
            quantityInput.classList.remove('border-red-500');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Update the maximum note dynamically
    if (maxQuantity !== null && latestTransactions[productSelect.value]) {
        const transaction = latestTransactions[productSelect.value];
        quantityNote.innerHTML = '<span class="text-orange-600">(Remaining: ' + maxQuantity + ' kg of ' + transaction.original_quantity + ' kg supplied)</span>';
    }
});

// Form validation before submit
stockinForm.addEventListener('submit', function(e) {
    if (maxQuantity !== null) {
        const enteredQuantity = parseFloat(quantityInput.value);
        
        if (enteredQuantity > maxQuantity) {
            e.preventDefault();
            alert('Error: Quantity (' + enteredQuantity + ' kg) exceeds remaining quantity (' + maxQuantity + ' kg). Please adjust the quantity.');
            return false;
        }
    }
});

// Trigger on product selection change
productSelect.addEventListener('change', autoFillFromSupplierTransaction);

// Trigger on page load if product is already selected
document.addEventListener('DOMContentLoaded', function() {
    autoFillFromSupplierTransaction();
});
</script>
@endsection