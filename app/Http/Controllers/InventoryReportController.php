<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\TransactionDetail;

class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date range (default: last 30 days)
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // ========== SIMPLE METRICS ==========
        
        // 1. Total Products
        $totalProducts = Product::count();
        
        // 2. Total Stock Value (Quantity × Price)
        $totalStockValue = Product::sum(DB::raw('Quantity_in_Stock * unit_price'));
        
        // 3. Low Stock Items (stock below 20)
        $lowStockCount = Product::where('Quantity_in_Stock', '<', 20)->count();
        
        // 4. Total Sales in Period
        $totalSales = SalesTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('total_amount');

        // ========== TOP SELLING PRODUCTS ==========
        $topSellingProducts = DB::table('transaction_details')
            ->join('sales_transactions', 'transaction_details.transaction_ID', '=', 'sales_transactions.transaction_ID')
            ->join('products', 'transaction_details.Product_ID', '=', 'products.Product_ID')
            ->whereBetween('sales_transactions.transaction_date', [$startDate, $endDate])
            ->where('sales_transactions.status', 'paid')
            ->select(
                'products.Product_Name',
                DB::raw('SUM(transaction_details.Quantity) as total_sold'),
                DB::raw('SUM(transaction_details.Quantity * transaction_details.unit_price) as total_revenue')
            )
            ->groupBy('products.Product_ID', 'products.Product_Name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // ========== SALES BY CATEGORY ==========
        $salesByCategory = DB::table('transaction_details')
            ->join('sales_transactions', 'transaction_details.transaction_ID', '=', 'sales_transactions.transaction_ID')
            ->join('products', 'transaction_details.Product_ID', '=', 'products.Product_ID')
            ->whereBetween('sales_transactions.transaction_date', [$startDate, $endDate])
            ->where('sales_transactions.status', 'paid')
            ->select(
                'products.Category',
                DB::raw('SUM(transaction_details.Quantity) as total_quantity'),
                DB::raw('SUM(transaction_details.Quantity * transaction_details.unit_price) as total_revenue')
            )
            ->groupBy('products.Category')
            ->orderByDesc('total_revenue')
            ->get();

        // ========== STOCK LEVELS BY CATEGORY ==========
        // Get all products grouped by category (for stock chart)
        // FIXED: Added 'variety' to the select statement
        $stockByCategory = Product::select(
                'Product_ID',
                'Product_Name',
                'Category',
                'variety',  // <- ADDED THIS LINE
                'Quantity_in_Stock',
                'unit_price',
                DB::raw('(Quantity_in_Stock * unit_price) as total_value')
            )
            ->orderBy('Category')
            ->orderBy('Product_Name')
            ->get()
            ->groupBy('Category');

        // ========== LOW STOCK PRODUCTS ==========
        $lowStockProducts = Product::where('Quantity_in_Stock', '<', 20)
            ->orderBy('Quantity_in_Stock', 'asc')
            ->limit(10)
            ->get();

        // ========== DAILY SALES TREND (Last 7 Days) ==========
        $dailySales = DB::table('sales_transactions')
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_amount) as daily_revenue')
            )
            ->whereBetween('transaction_date', [Carbon::now()->subDays(7), Carbon::now()])
            ->where('status', 'paid')
            ->groupBy(DB::raw('DATE(transaction_date)'))
            ->orderBy('date', 'asc')
            ->get();

        return view('inventory.reports', compact(
            'startDate',
            'endDate',
            'totalProducts',
            'totalStockValue',
            'lowStockCount',
            'totalSales',
            'topSellingProducts',
            'salesByCategory',
            'stockByCategory',
            'lowStockProducts',
            'dailySales'
        ));
    }

    // Get data via AJAX
    public function getData(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        return response()->json([
            'totalProducts' => Product::count(),
            'totalStockValue' => Product::sum(DB::raw('Quantity_in_Stock * unit_price')),
            'lowStockCount' => Product::where('Quantity_in_Stock', '<', 20)->count(),
            'totalSales' => SalesTransaction::whereBetween('transaction_date', [$startDate, $endDate])
                ->where('status', 'paid')
                ->sum('total_amount')
        ]);
    }

    // Export to PDF
    public function export(Request $request)
    {
        return redirect()->back()->with('info', 'PDF export feature coming soon!');
    }
}