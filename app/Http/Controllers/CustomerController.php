<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Load customers with ALL their sales transactions
        $customers = Customer::with('sales')->get();
        return view('customers.index', compact('customers'));
    }

    public function show($id)
    {
        // Load customer with all necessary relationships
        $customer = Customer::with([
            'sales' => function($query) {
                $query->orderBy('transaction_date', 'desc');
            },
            'sales.details.product',  // Load transaction details and their products
            'sales.user'              // Load the user (cashier) for each transaction
        ])->findOrFail($id);
        
        return view('customers.show', compact('customer'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Customer_Name' => 'required',
            'Contact_Number' => 'nullable',
        ]);

        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Customer_Name' => 'required',
            'Contact_Number' => 'nullable',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}