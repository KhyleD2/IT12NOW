@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md mt-10">
    <h1 class="text-3xl font-bold mb-6">Add Supplier Transaction</h1>

    <form method="POST" action="{{ route('supplier-transactions.store') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf

        <div>
            <label>Supplier</label>
            <select name="Supplier_ID" id="supplier-select" class="w-full p-2 border" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->Supplier_ID }}">{{ $supplier->Supplier_Name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Product</label>
            <select name="Product_ID" id="product-select" class="w-full p-2 border" required>
                <option value="">Select Product</option>
            </select>
        </div>

        <div>
            <label>Quantity Units</label>
            <input type="number" name="quantity_units" id="quantity-units" min="0" class="w-full p-2 border" required>
        </div>

        <div>
            <label>Quantity Kilos</label>
            <input type="number" name="quantity_kilos" id="quantity-kilos" step="0.01" min="0" class="w-full p-2 border" required>
        </div>

        <div>
            <label>Supply Date</label>
            <input type="date" name="supply_date" class="w-full p-2 border" required>
        </div>

        <div>
            <label>Total Cost</label>
            <input type="number" name="total_cost" id="total-cost" step="0.01" readonly class="w-full p-2 border" required>
        </div>

        <div>
            <label>Status</label>
            <select name="status" class="w-full p-2 border" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
    </form>
</div>

<script>
const supplierSelect = document.getElementById('supplier-select');
const productSelect = document.getElementById('product-select');
const quantityUnits = document.getElementById('quantity-units');
const quantityKilos = document.getElementById('quantity-kilos');
const totalCost = document.getElementById('total-cost');

let productsBySupplier = @json($productsBySupplier);

supplierSelect.addEventListener('change', function() {
    const supplierId = this.value;
    productSelect.innerHTML = '<option value="">Select Product</option>';
    if(productsBySupplier[supplierId]){
        productsBySupplier[supplierId].forEach(p=>{
            const option = document.createElement('option');
            option.value = p.Product_ID;
            option.dataset.price = p.price;
            option.dataset.units = p.default_units;
            option.dataset.kilos = p.default_kilos;
            option.textContent = p.name;
            productSelect.appendChild(option);
        });
    }
    quantityUnits.value = '';
    quantityKilos.value = '';
    totalCost.value = '';
});

productSelect.addEventListener('change', function(){
    const selected = this.selectedOptions[0];
    if(selected){
        quantityUnits.value = selected.dataset.units;
        quantityKilos.value = selected.dataset.kilos;
        calculateTotal();
    }
});

quantityKilos.addEventListener('input', calculateTotal);

function calculateTotal(){
    const price = parseFloat(productSelect.selectedOptions[0]?.dataset.price || 0);
    const kilos = parseFloat(quantityKilos.value || 0);
    totalCost.value = (price*kilos).toFixed(2);
}
</script>
@endsection
