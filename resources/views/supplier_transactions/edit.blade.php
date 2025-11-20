@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md mt-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Supplier Transaction</h1>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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

        <!-- Quantity Units -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Quantity Units</label>
            <input type="number" name="quantity_units" id="quantity-units" min="0" step="1" 
                   value="{{ old('quantity_units', $supplier_transaction->quantity_units ?? 0) }}"
                   class="w-full p-3 border border-gray-300 rounded" required>
        </div>

        <!-- Quantity Kilos -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Quantity Kilos</label>
            <input type="number" name="quantity_kilos" id="quantity-kilos" step="0.01" min="0" 
                   value="{{ old('quantity_kilos', $supplier_transaction->quantity_kilos ?? 0) }}"
                   class="w-full p-3 border border-gray-300 rounded" required>
        </div>

        <!-- Price per Kg -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Price per Kg</label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-600 font-semibold">₱</span>
                <input type="number" name="supplier_price" id="supplier-price" step="0.01" min="0" 
                       value="{{ old('supplier_price', $supplier_transaction->supplier_price ?? 0) }}"
                       class="w-full p-3 pl-8 border border-gray-300 rounded" required>
            </div>
        </div>

        <!-- Supply Date -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Supply Date</label>
            <input type="date" name="supply_date" id="supply-date"
                   value="{{ old('supply_date', $supplier_transaction->supply_date->format('Y-m-d')) }}" 
                   class="w-full p-3 border border-gray-300 rounded" required>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Status</label>
            <select name="status" id="status-select" class="w-full p-3 border border-gray-300 rounded" required>
                <option value="pending" {{ old('status', $supplier_transaction->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status', $supplier_transaction->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ old('status', $supplier_transaction->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="paid" {{ old('status', $supplier_transaction->status) == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
            <p class="text-xs text-green-600 mt-1 font-semibold">
                ⚠️ <strong>Note:</strong> Selecting "Completed" will automatically add this quantity to your stock inventory!
            </p>
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
    const statusSelect = document.getElementById('status-select');

    let productsBySupplier = @json($productsBySupplier);

    function updateProducts() {
        const supplierId = supplierSelect.value;
        productSelect.innerHTML = '<option value="">Select Product</option>';

        if (supplierId && productsBySupplier[supplierId]) {
            productsBySupplier[supplierId].forEach(product => {
                const option = document.createElement('option');
                option.value = product.Product_ID;
                option.textContent = product.name;

                // Preselect current product
                if (product.Product_ID == "{{ $supplier_transaction->Product_ID }}") {
                    option.selected = true;
                }

                productSelect.appendChild(option);
            });
        }
    }

    // Initial load
    updateProducts();

    supplierSelect.addEventListener('change', updateProducts);

    // Show confirmation when selecting "Completed" status
    statusSelect.addEventListener('change', function() {
        if(this.value === 'completed') {
            const quantityUnits = parseFloat(document.getElementById('quantity-units').value) || 0;
            const quantityKilos = parseFloat(document.getElementById('quantity-kilos').value) || 0;
            const totalQty = quantityUnits + quantityKilos;
            
            if(totalQty > 0 && productSelect.value) {
                const productName = productSelect.options[productSelect.selectedIndex].text;
                const confirmation = confirm(
                    `Stock will be automatically updated:\n\n` +
                    `Product: ${productName}\n` +
                    `Quantity: ${totalQty} kg\n\n` +
                    `This will add the stock to your inventory.\n` +
                    `Continue?`
                );
                
                if (!confirmation) {
                    this.value = 'pending';
                }
            }
        }
    });
</script>
@endsection