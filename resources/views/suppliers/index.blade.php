@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-3xl font-extrabold text-green-700">Suppliers</h1>
        <a href="{{ route('suppliers.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
            + Add Supplier
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md animate-fade">
        {{ session('success') }}
    </div>
    @endif

    <!-- Suppliers Table -->
    <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-green-700 text-white rounded-t-xl">
                <tr>
                    <th class="py-3 px-4 text-left text-sm font-semibold">ID</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Supplier Name</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Contact Person</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Contact Number</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Address</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Payment Terms</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($suppliers as $supplier)
                <tr class="hover:bg-green-50 transition">
                    <td class="py-2 px-4 text-sm font-medium text-gray-700">{{ $supplier->Supplier_ID }}</td>
                    <td class="py-2 px-4 text-sm text-gray-800">{{ $supplier->Supplier_Name }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $supplier->contact_person }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $supplier->contact_number }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $supplier->address }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700 font-semibold">
                        {{ $supplier->payment_terms }}
                    </td>
                    <td class="py-2 px-4 flex gap-2">
                        <a href="{{ route('suppliers.edit', $supplier->Supplier_ID) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg shadow-sm transition transform hover:scale-105">
                            Edit
                        </a>
                        <form action="{{ route('suppliers.destroy', $supplier->Supplier_ID) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg shadow-sm transition transform hover:scale-105">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
