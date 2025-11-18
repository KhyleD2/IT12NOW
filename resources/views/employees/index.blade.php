@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Employee List</h2>
    
    @if($employees->count() > 0)
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-green-700 text-white">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr class="hover:bg-green-100">
                    <td class="p-2 border">{{ $employee->id }}</td>
                    <td class="p-2 border">{{ $employee->fname }} {{ $employee->lname }}</td>
                    <td class="p-2 border">{{ $employee->email }}</td>
                    <td class="p-2 border">{{ ucfirst($employee->role) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500">No employees found.</p>
    @endif
</div>
@endsection
