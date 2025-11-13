@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-green-700">Edit Supplier</h1>

@if ($errors->any())
<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('suppliers.update', $supplier->Supplier_ID) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-gray-700 font-semibold mb-1">Supplier Name</label>
        <input type="text" name="Supplier_Name" value="{{ $supplier->Supplier_Name }}" 
               class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
    </div>

    <div>
        <label class="block text-gray-700 font-semibold mb-1">Contact Person</label>
        <input type="text" name="contact_person" value="{{ $supplier->contact_person }}" 
               class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
    </div>

    <div>
        <label class="block text-gray-700 font-semibold mb-1">Contact Number</label>
        <input type="text" name="contact_number" value="{{ $supplier->contact_number }}" 
               class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
    </div>

    <div>
        <label class="block text-gray-700 font-semibold mb-1">Address</label>
        <input type="text" name="address" value="{{ $supplier->address }}" 
               class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
    </div>

    <!-- Payment Terms Dropdown -->
    <div>
        <label class="block text-gray-700 font-semibold mb-1">Payment Terms</label>
        <select name="payment_terms" required 
                class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="Cash" {{ $supplier->payment_terms === 'Cash' ? 'selected' : '' }}>Cash</option>
            <option value="GCash" {{ $supplier->payment_terms === 'GCash' ? 'selected' : '' }}>GCash</option>
        </select>
    </div>

    <div class="flex justify-end">
        <button type="submit" 
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
            Update Supplier
        </button>
    </div>
</form>
@endsection
