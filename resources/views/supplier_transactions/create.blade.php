@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md mt-10">
    <h1 class="text-3xl font-bold mb-6">Add Supplier Transaction</h1>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('supplier-transactions.store') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf

        <div>
            <label class="block mb-2 font-medium">Supplier</label>
            <select name="Supplier_ID" id="supplier-select" class="w-full p-2 border rounded" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->Supplier_ID }}">{{ $supplier->Supplier_Name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">Product</label>
            <select name="Product_ID" id="product-select" class="w-full p-2 border rounded" required>
                <option value="">Select Product</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium text-blue-700">Price per Kg</label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-600 font-semibold">₱</span>
                <input type="number" name="supplier_price" id="supplier-price" step="0.01" min="0" value="0" 
                       class="w-full p-2 pl-8 border-2 rounded border-blue-300 bg-blue-50 font-semibold" required>
            </div>
            <p class="text-xs text-blue-600 mt-1">Enter supplier's price per kilogram</p>
        </div>

        <div>
            <label class="block mb-2 font-medium">Quantity Units</label>
            <input type="number" name="quantity_units" id="quantity-units" min="0" step="1" value="0" 
                   class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Quantity Kilos</label>
            <input type="number" name="quantity_kilos" id="quantity-kilos" step="0.01" min="0" value="0" 
                   class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Supply Date</label>
            <input type="date" name="supply_date" value="{{ date('Y-m-d') }}" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Total Cost</label>
            <input type="text" id="total-cost-display" value="₱0.00" readonly 
                   class="w-full p-2 border rounded bg-gray-100 font-bold text-lg text-green-700">
            <input type="hidden" name="total_cost" id="total-cost-hidden" value="0">
        </div>

        <div>
            <label class="block mb-2 font-medium">Status</label>
            <select name="status" id="status-select" class="w-full p-2 border rounded" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <p class="text-xs text-green-600 mt-1 font-semibold">
                ⚠️ <strong>Note:</strong> Selecting "Completed" will automatically add this quantity to your stock inventory!
            </p>
        </div>

        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 font-medium w-full">
            Save Transaction
        </button>
    </form>
</div>

<script>
const supplierSelect = document.getElementById('supplier-select');
const productSelect = document.getElementById('product-select');
const supplierPrice = document.getElementById('supplier-price');
const quantityUnits = document.getElementById('quantity-units');
const quantityKilos = document.getElementById('quantity-kilos');
const totalCostDisplay = document.getElementById('total-cost-display');
const totalCostHidden = document.getElementById('total-cost-hidden');
const statusSelect = document.getElementById('status-select');

let productsBySupplier = @json($productsBySupplier);

supplierSelect.addEventListener('change', function() {
    const supplierId = this.value;
    productSelect.innerHTML = '<option value="">Select Product</option>';
    
    if(productsBySupplier[supplierId]){
        productsBySupplier[supplierId].forEach(p => {
            const option = document.createElement('option');
            option.value = p.Product_ID;
            option.textContent = p.name;
            productSelect.appendChild(option);
        });
    }
    
    resetFields();
});

productSelect.addEventListener('change', function(){
    if(!this.value) {
        resetFields();
    }
});

// Calculate on any input change
supplierPrice.addEventListener('input', calculateTotal);
quantityUnits.addEventListener('input', calculateTotal);
quantityKilos.addEventListener('input', calculateTotal);

function calculateTotal(){
    const price = parseFloat(supplierPrice.value) || 0;
    const units = parseFloat(quantityUnits.value) || 0;
    const kilos = parseFloat(quantityKilos.value) || 0;
    
    // Total = (units + kilos) × price
    const total = (units + kilos) * price;
    
    totalCostDisplay.value = '₱' + total.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    totalCostHidden.value = total.toFixed(2);
}

function resetFields() {
    supplierPrice.value = '0';
    quantityUnits.value = '0';
    quantityKilos.value = '0';
    totalCostDisplay.value = '₱0.00';
    totalCostHidden.value = '0';
}

// Show confirmation when selecting "Completed" status
statusSelect.addEventListener('change', function() {
    if(this.value === 'completed') {
        const totalQty = (parseFloat(quantityUnits.value) || 0) + (parseFloat(quantityKilos.value) || 0);
        if(totalQty > 0 && productSelect.value) {
            const productName = productSelect.options[productSelect.selectedIndex].text;
            const confirmation = confirm(
                `Stock will be automatically updated:\n\n` +
                `Product: ${productName}\n` +
                `Quantity: ${totalQty} kg\n\n` +
                `This will add the stock to your inventory.\n` +
                `You will be redirected to the transaction list.\n` +
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