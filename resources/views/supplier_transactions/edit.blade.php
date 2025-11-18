@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md mt-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Supplier Transaction</h1>

    <form method="POST" action="{{ route('supplier-transactions.update', $supplier_transaction->Supply_transac_ID) }}" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PUT')

        <!-- Supplier -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Supplier</label>
            <select id="supplier-select" name="Supplier_ID" class="w-full p-3 border border-gray-300 rounded" required>
                <option value="">Select Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->Supplier_ID }}" 
                        {{ $supplier->Supplier_ID == $supplier_transaction->Supplier_ID ? 'selected' : '' }}>
                        {{ $supplier->Supplier_Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Product -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Product</label>
            <select id="product-select" name="Product_ID" class="w-full p-3 border border-gray-300 rounded" required>
                <option value="">Select Product</option>
            </select>
        </div>

        <!-- Quantity in Kilos -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Quantity (Kilos)</label>
            <input type="number" step="0.01" name="quantity_supplier" id="quantity-kilo" class="w-full p-3 border border-gray-300 rounded" required
                value="{{ $supplier_transaction->quantity_supplier }}">
        </div>

        <!-- Total Cost -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Total Cost</label>
            <input type="number" step="0.01" name="total_cost" id="total-cost" class="w-full p-3 border border-gray-300 rounded" readonly
                value="{{ $supplier_transaction->total_cost }}">
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 mt-4">
            <a href="{{ route('supplier.transactions') }}" class="px-6 py-3 border border-gray-300 rounded hover:bg-gray-200 transition">Cancel</a>
            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700 transition">Update</button>
        </div>
    </form>
</div>

<script>
    const supplierSelect = document.getElementById('supplier-select');
    const productSelect = document.getElementById('product-select');
    const quantityInput = document.getElementById('quantity-kilo');
    const totalCostInput = document.getElementById('total-cost');

    let productsBySupplier = @json($productsBySupplier);

    function updateProducts() {
        const supplierId = supplierSelect.value;
        productSelect.innerHTML = '<option value="">Select Product</option>';

        if (supplierId && productsBySupplier[supplierId]) {
            productsBySupplier[supplierId].forEach(product => {
                const option = document.createElement('option');
                option.value = product.Product_ID;
                option.dataset.price = product.price;
                option.textContent = product.name;

                // Preselect current product
                if (product.Product_ID == "{{ $supplier_transaction->Product_ID }}") {
                    option.selected = true;
                }

                productSelect.appendChild(option);
            });
        }
        calculateTotal();
    }

    function calculateTotal() {
        const price = parseFloat(productSelect.selectedOptions[0]?.dataset.price || 0);
        const quantity = parseFloat(quantityInput.value || 0);
        totalCostInput.value = (price * quantity).toFixed(2);
    }

    // Initial load
    updateProducts();

    supplierSelect.addEventListener('change', updateProducts);
    productSelect.addEventListener('change', calculateTotal);
    quantityInput.addEventListener('input', calculateTotal);
</script>
@endsection
