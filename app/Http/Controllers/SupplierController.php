<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierTransaction;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // -----------------------------
    // SUPPLIERS CRUD
    // -----------------------------
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Supplier_Name' => 'required',
            'contact_person' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
            'payment_terms' => 'required',
        ]);

        Supplier::create($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'Supplier_Name' => 'required',
            'contact_person' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
            'payment_terms' => 'required',
        ]);

        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    // -----------------------------
    // SUPPLIER TRANSACTIONS
    // -----------------------------
    public function transactions()
{
    $transactions = SupplierTransaction::with(['supplier', 'product'])->latest()->get();
    return view('suppliers.transactions', compact('transactions'));
}

}
