<?php
// app/Http/Controllers/SupplierTransactionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierTransaction;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockIn;

class SupplierTransactionController extends Controller
{
    private function loadSupplierProducts()
    {
        $suppliers = Supplier::with('products')->get();

        $productsBySupplier = [];
        foreach ($suppliers as $supplier) {
            $productsBySupplier[$supplier->Supplier_ID] = $supplier->products->map(function ($product) {
                return [
                    'Product_ID' => $product->Product_ID,
                    'name' => $product->Product_Name,
                ];
            });
        }

        return compact('suppliers', 'productsBySupplier');
    }

    public function index()
    {
        $transactions = SupplierTransaction::with(['supplier', 'product'])
            ->orderBy('Supply_transac_ID', 'DESC')
            ->get();

        return view('supplier_transactions.index', compact('transactions'));
    }

    public function create()
    {
        $data = $this->loadSupplierProducts();
        return view('supplier_transactions.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'Product_ID' => 'required|exists:products,Product_ID',
            'supplier_price' => 'required|numeric|min:0',
            'quantity_units' => 'required|numeric|min:0',
            'quantity_kilos' => 'required|numeric|min:0',
            'supply_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // Calculate total_cost from manual price input
        $total_cost = ($request->quantity_units + $request->quantity_kilos) * $request->supplier_price;

        $transaction = SupplierTransaction::create([
            'Supplier_ID' => $request->Supplier_ID,
            'Product_ID' => $request->Product_ID,
            'quantity_units' => $request->quantity_units,
            'quantity_kilos' => $request->quantity_kilos,
            'supply_date' => $request->supply_date,
            'total_cost' => $total_cost,
            'status' => $request->status,
        ]);

        // REMOVED: Automatic stock-in creation when status is completed
        // Just save the transaction, don't create stock-in or update inventory yet
        
        if ($request->status === 'completed') {
            return redirect()->route('supplier.transactions')
                ->with('success', 'Transaction completed! Please go to Stock In → Add Stock to update inventory.');
        }

        return redirect()->route('supplier.transactions')
            ->with('success', 'Transaction added successfully.');
    }

    public function edit(SupplierTransaction $supplier_transaction)
    {
        $data = $this->loadSupplierProducts();

        return view('supplier_transactions.edit', 
            array_merge($data, ['supplier_transaction' => $supplier_transaction])
        );
    }

    public function update(Request $request, SupplierTransaction $supplier_transaction)
    {
        $request->validate([
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'Product_ID' => 'required|exists:products,Product_ID',
            'supplier_price' => 'required|numeric|min:0',
            'quantity_units' => 'required|numeric|min:0',
            'quantity_kilos' => 'required|numeric|min:0',
            'supply_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled,paid',
        ]);

        // Calculate total_cost from manual price input
        $total_cost = ($request->quantity_units + $request->quantity_kilos) * $request->supplier_price;

        // Get old status before update
        $oldStatus = $supplier_transaction->status;
        
        $supplier_transaction->update([
            'Supplier_ID' => $request->Supplier_ID,
            'Product_ID' => $request->Product_ID,
            'quantity_units' => $request->quantity_units,
            'quantity_kilos' => $request->quantity_kilos,
            'supply_date' => $request->supply_date,
            'total_cost' => $total_cost,
            'status' => $request->status,
        ]);

        // REMOVED: Automatic stock-in creation when changing to completed
        // Just update the transaction, don't create stock-in or update inventory
        
        if ($oldStatus !== 'completed' && $request->status === 'completed') {
            return redirect()->route('supplier.transactions')
                ->with('success', 'Transaction marked as completed! Please go to Stock In → Add Stock to update inventory.');
        }

        return redirect()->route('supplier.transactions')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(SupplierTransaction $supplier_transaction)
    {
        $supplier_transaction->delete();

        return redirect()->route('supplier.transactions')
            ->with('success', 'Transaction deleted successfully.');
    }

    public function pay(SupplierTransaction $supplier_transaction)
    {
        if ($supplier_transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transactions can be paid.');
        }

        $supplier_transaction->update([
            'status' => 'paid'
        ]);

        return redirect()->route('supplier.transactions')
            ->with('success', 'Transaction marked as paid.');
    }

    public function printReceipt(SupplierTransaction $supplier_transaction)
    {
        $supplier_transaction->load(['supplier', 'product']);

        return view('supplier_transactions.receipt', [
            'transaction' => $supplier_transaction
        ]);
    }
}