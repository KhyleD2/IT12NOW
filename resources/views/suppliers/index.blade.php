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
                    <td class="py-2 px-4 text-sm text-gray-700 font-semibold">{{ $supplier->payment_terms }}</td>

                    <!-- Action Buttons -->
                    <td class="py-2 px-4 flex gap-2">

                        <!-- Edit Button -->
                        <a href="{{ route('suppliers.edit', $supplier->Supplier_ID) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg shadow-sm transition transform hover:scale-105 flex items-center justify-center"
                            title="Edit">
                            <!-- Pencil Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.183L7.5 19.215 3 21l1.784-4.5 12.078-13.013z" />
                            </svg>
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('suppliers.destroy', $supplier->Supplier_ID) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg shadow-sm transition transform hover:scale-105 flex items-center justify-center"
                                title="Delete">
                                <!-- Trash Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21a48.108 48.108 0 00-14.456 0M5.5 6.79L6.26 19.21A2.25 2.25 0 008.506 21h6.988a2.25 2.25 0 002.245-1.79L18.5 6.79M10 6V4.5A1.5 1.5 0 0111.5 3h1A1.5 1.5 0 0114 4.5V6" />
                                </svg>
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
