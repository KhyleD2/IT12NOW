@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <span class="material-icons text-green-600 text-4xl mr-3">manage_accounts</span>
                <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('users.create-cashier') }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center">
                    <span class="material-icons mr-2">person_add</span>
                    Create Cashier
                </a>
                <a href="{{ route('users.create-manager') }}" 
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center">
                    <span class="material-icons mr-2">admin_panel_settings</span>
                    Create Manager
                </a>
            </div>
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

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Contact</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $user->User_ID }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $user->fname }} {{ $user->lname }}</td>
                            <td class="px-4 py-3 text-sm">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm">{{ $user->contact_number ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $user->role === 'manager' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $user->role === 'cashier' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($user->role !== 'admin')
                                    <form action="{{ route('users.destroy', $user->User_ID) }}" method="POST" 
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm flex items-center mx-auto">
                                            <span class="material-icons text-sm mr-1">delete</span>
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="mt-6 flex gap-4 text-sm text-gray-600">
            <div class="flex items-center">
                <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                Admin: {{ $users->where('role', 'admin')->count() }}
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                Manager: {{ $users->where('role', 'manager')->count() }}
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                Cashier: {{ $users->where('role', 'cashier')->count() }}
            </div>
            <div class="ml-auto font-semibold">
                Total Users: {{ $users->count() }}
            </div>
        </div>
    </div>
</div>
@endsection