<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockIn;
use App\Models\Product;
use App\Models\SupplierTransaction;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with('product')->orderBy('date', 'desc')->get();
        $products = Product::with('supplier')->get();
        
        return view('stockins.index', compact('stockIns', 'products'));
    }

    public function create()
    {
        $products = Product::with('supplier')->get();
        
        // Get latest COMPLETED supplier transactions with remaining quantity
        $latestSupplierTransactions = [];
        
        foreach ($products as $product) {
            // Find the most recent completed/paid transaction
            $latest = SupplierTransaction::where('Product_ID', $product->Product_ID)
                ->whereIn('status', ['completed', 'paid'])
                ->latest('Supply_transac_ID')
                ->first();
            
            if ($latest) {
                // FIXED: Only use quantity_units, not adding quantity_kilos
                $suppliedQty = (float)$latest->quantity_units;
                
                // Calculate how much has already been stocked in from THIS SPECIFIC transaction
                $alreadyStockedQty = StockIn::where('supplier_transaction_id', $latest->Supply_transac_ID)
                    ->sum('quantity');
                
                // Calculate remaining quantity available for stock-in
                $remainingQty = $suppliedQty - $alreadyStockedQty;
                
                // Only show if there's remaining quantity
                if ($remainingQty > 0) {
                    // Calculate price per kg from supplier transaction
                    $pricePerKg = $suppliedQty > 0 ? ($latest->total_cost / $suppliedQty) : 0;
                    
                    $latestSupplierTransactions[$product->Product_ID] = [
                        'quantity' => $remainingQty,
                        'original_quantity' => $suppliedQty,
                        'already_stocked' => $alreadyStockedQty,
                        'price' => round($pricePerKg, 2),
                        'date' => $latest->supply_date,
                        'transaction_id' => $latest->Supply_transac_ID,
                    ];
                }
            }
        }
        
        return view('stockins.create', compact('products', 'latestSupplierTransactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Product_ID' => 'required|exists:products,Product_ID',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'expiry_date' => 'nullable|date',
            'critical_level' => 'required|integer|min:0',
            'supplier_transaction_id' => 'nullable|exists:supplier_transactions,Supply_transac_ID',
        ]);

        // SERVER-SIDE VALIDATION: Check remaining quantity if linked to supplier transaction
        if ($request->supplier_transaction_id) {
            $transaction = SupplierTransaction::findOrFail($request->supplier_transaction_id);
            
            // FIXED: Only use quantity_units, not adding quantity_kilos
            $suppliedQty = (float)$transaction->quantity_units;
            
            // Calculate already stocked quantity from this specific transaction
            $alreadyStockedQty = StockIn::where('supplier_transaction_id', $request->supplier_transaction_id)
                ->sum('quantity');
            
            $remainingQty = $suppliedQty - $alreadyStockedQty;
            
            if ($request->quantity > $remainingQty) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'quantity' => 'Quantity (' . $request->quantity . ' kg) exceeds remaining quantity (' . round($remainingQty, 2) . ' kg). Already stocked: ' . round($alreadyStockedQty, 2) . ' kg out of ' . round($suppliedQty, 2) . ' kg supplied.'
                    ]);
            }
        }

        // Create the stock-in record with supplier transaction link
        StockIn::create($request->all());

        // Update product stock in inventory
        $product = Product::findOrFail($request->Product_ID);
        $product->updateFromStockIns();

        return redirect()->route('stockins.index')
            ->with('success', 'Stock added successfully and inventory updated! Quantity: ' . $request->quantity . ' kg');
    }

    public function edit(StockIn $stockin)
    {
        $products = Product::with('supplier')->get();
        return view('stockins.edit', compact('stockin', 'products'));
    }

    public function update(Request $request, StockIn $stockin)
    {
        $request->validate([
            'Product_ID' => 'required|exists:products,Product_ID',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'expiry_date' => 'nullable|date',
            'critical_level' => 'required|integer|min:0',
        ]);

        $stockin->update($request->all());

        // Update product stock
        $product = Product::findOrFail($request->Product_ID);
        $product->updateFromStockIns();

        return redirect()->route('stockins.index')
            ->with('success', 'Stock updated successfully.');
    }

    public function destroy(StockIn $stockin)
    {
        $productId = $stockin->Product_ID;
        $stockin->delete();

        // Update product stock after deletion
        $product = Product::findOrFail($productId);
        $product->updateFromStockIns();

        return redirect()->route('stockins.index')
            ->with('success', 'Stock deleted successfully.');
    }
}