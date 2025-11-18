<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierTransaction;
use App\Models\Supplier;
use App\Models\Product;

class SupplierTransactionController extends Controller
{
    /** Load supplier + product list for forms */
    private function loadSupplierProducts()
    {
        $suppliers = Supplier::with('products')->get();

        $productsBySupplier = [];
        foreach ($suppliers as $supplier) {
            $productsBySupplier[$supplier->Supplier_ID] = $supplier->products->map(function ($product) {
                return [
                    'Product_ID' => $product->Product_ID,
                    'name' => $product->Product_Name,
                    'price' => $product->unit_price,
                    'default_units' => $product->Quantity_in_Stock,
                    'default_kilos' => $product->Quantity_in_Stock,
                ];
            });
        }

        return compact('suppliers', 'productsBySupplier');
    }

    /** INDEX */
    public function index()
    {
        $transactions = SupplierTransaction::with(['supplier', 'product'])
            ->orderBy('Supply_transac_ID', 'DESC')
            ->get();

        return view('supplier_transactions.index', compact('transactions'));
    }

    /** CREATE */
    public function create()
    {
        $data = $this->loadSupplierProducts();
        return view('supplier_transactions.create', $data);
    }

    /** STORE */
    public function store(Request $request)
    {
        $request->validate([
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'Product_ID' => 'required|exists:products,Product_ID',
            'quantity_units' => 'required|numeric|min:0',
            'quantity_kilos' => 'required|numeric|min:0',
            'supply_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // Fetch product to compute total cost
        $product = Product::findOrFail($request->Product_ID);
        $total_cost = ($request->quantity_units + $request->quantity_kilos) * $product->unit_price;

        SupplierTransaction::create([
            'Supplier_ID' => $request->Supplier_ID,
            'Product_ID' => $request->Product_ID,
            'quantity_units' => $request->quantity_units,
            'quantity_kilos' => $request->quantity_kilos,
            'supply_date' => $request->supply_date,
            'total_cost' => $total_cost,
            'status' => $request->status,
        ]);

        return redirect()->route('supplier.transactions')
            ->with('success', 'Transaction added successfully.');
    }

    /** EDIT */
    public function edit(SupplierTransaction $supplier_transaction)
    {
        $data = $this->loadSupplierProducts();

        return view('supplier_transactions.edit', 
            array_merge($data, ['supplier_transaction' => $supplier_transaction])
        );
    }

    /** UPDATE */
    public function update(Request $request, SupplierTransaction $supplier_transaction)
    {
        $request->validate([
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'Product_ID' => 'required|exists:products,Product_ID',
            'quantity_units' => 'required|numeric|min:0',
            'quantity_kilos' => 'required|numeric|min:0',
            'supply_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled,paid',
        ]);

        // Recompute cost
        $product = Product::findOrFail($request->Product_ID);
        $total_cost = ($request->quantity_units + $request->quantity_kilos) * $product->unit_price;

        $supplier_transaction->update([
            'Supplier_ID' => $request->Supplier_ID,
            'Product_ID' => $request->Product_ID,
            'quantity_units' => $request->quantity_units,
            'quantity_kilos' => $request->quantity_kilos,
            'supply_date' => $request->supply_date,
            'total_cost' => $total_cost,
            'status' => $request->status,
        ]);

        return redirect()->route('supplier.transactions')
            ->with('success', 'Transaction updated successfully.');
    }

    /** DELETE */
    public function destroy(SupplierTransaction $supplier_transaction)
    {
        $supplier_transaction->delete();

        return redirect()->route('supplier.transactions')
            ->with('success', 'Transaction deleted successfully.');
    }

    /** MARK AS PAID */
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

    /** PRINT RECEIPT */
    public function printReceipt(SupplierTransaction $supplier_transaction)
    {
        $supplier_transaction->load(['supplier', 'product']);

        return view('supplier_transactions.receipt', [
            'transaction' => $supplier_transaction
        ]);
    }
}
