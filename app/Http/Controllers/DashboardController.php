<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SalesTransaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();
        $totalSales = SalesTransaction::count();

        // Revenue calculations
        $todayRevenue = SalesTransaction::whereDate('created_at', Carbon::today())
            ->sum('total_amount');
        
        $weekRevenue = SalesTransaction::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total_amount');
        
        $monthRevenue = SalesTransaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // Daily revenue for last 7 days (for chart)
        $dailyRevenue = SalesTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format daily revenue for chart
        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::now()->subDays($i)->format('M d');
            $revenue = $dailyRevenue->firstWhere('date', $date);
            $dailyData[] = $revenue ? (float)$revenue->revenue : 0;
        }

        // Weekly revenue for last 4 weeks (for chart)
        $weeklyRevenue = [];
        $weeklyLabels = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $revenue = SalesTransaction::whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('total_amount');
            
            $weeklyRevenue[] = (float)$revenue;
            $weeklyLabels[] = $weekStart->format('M d');
        }

        // Monthly revenue for last 6 months (for chart)
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            $revenue = SalesTransaction::whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_amount');
            
            $monthlyRevenue[] = (float)$revenue;
            $monthlyLabels[] = $monthStart->format('M Y');
        }

        // Top 5 selling products
        $topProducts = TransactionDetail::select(
                'products.Product_Name as name',
                'products.Product_ID as id',
                DB::raw('SUM(transaction_details.Quantity) as total_sold'),
                DB::raw('SUM(transaction_details.Quantity * transaction_details.unit_price) as total_revenue')
            )
            ->join('products', 'transaction_details.Product_ID', '=', 'products.Product_ID')
            ->groupBy('products.Product_ID', 'products.Product_Name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Low stock alerts (products with stock less than 10 or a threshold)
        $lowStockThreshold = 10;
        $lowStockProducts = Product::select(
                'Product_ID as id',
                'Product_Name as name',
                'Quantity_in_Stock as stock_quantity',
                'unit_price'
            )
            ->where('Quantity_in_Stock', '<=', $lowStockThreshold)
            ->where('Quantity_in_Stock', '>', 0)
            ->orderBy('Quantity_in_Stock', 'asc')
            ->get();

        // Out of stock products
        $outOfStockCount = Product::where('Quantity_in_Stock', 0)->count();

        // Expiring products (only if expiry_date column exists)
        $expiringProducts = collect();
        $expiredProducts = collect();
        $expiringSoonProducts = collect();

        if (Schema::hasColumn('products', 'expiry_date')) {
            // Get all products with expiry dates
            $products = Product::whereNotNull('expiry_date')->get();
            
            foreach ($products as $product) {
                $expiryDate = Carbon::parse($product->expiry_date);
                $today = Carbon::today();
                $daysLeft = $today->diffInDays($expiryDate, false);
                $product->days_until_expiry = $daysLeft;

                if ($daysLeft <= 0) {
                    $expiredProducts->push($product);
                } elseif ($daysLeft > 0 && $daysLeft <= 7) {
                    $expiringSoonProducts->push($product);
                }
            }

            // Keep the old expiringProducts for backward compatibility
            $expiringProducts = Product::select(
                    'Product_ID as id',
                    'Product_Name as name',
                    'expiry_date',
                    'Quantity_in_Stock as stock_quantity'
                )
                ->whereNotNull('expiry_date')
                ->whereBetween('expiry_date', [
                    Carbon::now(),
                    Carbon::now()->addDays(7)
                ])
                ->orderBy('expiry_date', 'asc')
                ->get();
        }

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalSuppliers' => $totalSuppliers,
            'totalSales' => $totalSales,
            'todayRevenue' => $todayRevenue,
            'weekRevenue' => $weekRevenue,
            'monthRevenue' => $monthRevenue,
            'dailyLabels' => $dailyLabels,
            'dailyData' => $dailyData,
            'weeklyLabels' => $weeklyLabels,
            'weeklyRevenue' => $weeklyRevenue,
            'monthlyLabels' => $monthlyLabels,
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockCount' => $outOfStockCount,
            'expiringProducts' => $expiringProducts,
            'expiredProducts' => $expiredProducts,
            'expiringSoonProducts' => $expiringSoonProducts,
        ]);
    }
}