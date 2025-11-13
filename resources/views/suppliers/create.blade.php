@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-lg">

    <h1 class="text-3xl font-bold text-green-700 mb-6">Add Supplier</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Supplier Name</label>
            <input type="text" name="Supplier_Name" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Contact Person</label>
            <input type="text" name="contact_person" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Contact Number</label>
            <input type="text" name="contact_number" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Address</label>
            <input type="text" name="address" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Payment Terms Dropdown -->
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Payment Terms</label>
            <select name="payment_terms" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="Cash">Cash</option>
                <option value="GCash">GCash</option>
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
                Save Supplier
            </button>
        </div>
    </form>
</div>
@endsection
