<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\ProductController;

// Root redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login routes (guests only)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventory / Products
    Route::resource('inventory', InventoryController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Sales Transactions
    Route::resource('sales', SalesTransactionController::class);

    // Extra route to mark a sale as paid
    Route::put('sales/{sale}/paid', [SalesTransactionController::class, 'markPaid'])->name('sales.markPaid');
    // Mark as paid
Route::put('/sales/{sale}/markPaid', [SalesTransactionController::class, 'markPaid'])->name('sales.markPaid');

// Print receipt
Route::get('/sales/{sale}/print', [SalesTransactionController::class, 'printReceipt'])->name('sales.printReceipt');

});
