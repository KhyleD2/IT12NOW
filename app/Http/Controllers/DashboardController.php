<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SalesTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalProducts' => Product::count(),
            'totalCustomers' => Customer::count(),
            'totalSuppliers' => Supplier::count(),
            'totalSales' => SalesTransaction::count(),
        ]);
    }
}
