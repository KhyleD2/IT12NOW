@extends('layouts.app')

@section('title', 'All Cashiers')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <span class="material-icons text-blue-600 text-4xl mr-3">people</span>
                <h2 class="text-2xl font-bold text-gray-800">All Cashiers</h2>
            </div>
            <a href="{{ route('users.create-cashier') }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center">
                <span class="material-icons mr-2">person_add</span>
                Create New Cashier
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-50 border-b">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">First Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Last Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Contact Number</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashiers as $cashier)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $cashier->User_ID }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $cashier->fname }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $cashier->lname }}</td>
                            <td class="px-4 py-3 text-sm">{{ $cashier->email }}</td>
                            <td class="px-4 py-3 text-sm">{{ $cashier->contact_number ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $cashier->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('users.destroy', $cashier->User_ID) }}" method="POST" 
                                    onsubmit="return confirm('Are you sure you want to delete this cashier?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm flex items-center mx-auto">
                                        <span class="material-icons text-sm mr-1">delete</span>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-6xl text-gray-300 mb-2">person_off</span>
                                    <p class="text-lg">No cashiers found.</p>
                                    <a href="{{ route('users.create-cashier') }}" class="mt-4 text-blue-600 hover:text-blue-700 underline">
                                        Create your first cashier account
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <span class="font-semibold text-blue-600">{{ $cashiers->count() }}</span> Total Cashiers
            </div>
            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">
                View All Users
            </a>
        </div>
    </div>
</div>
@endsection