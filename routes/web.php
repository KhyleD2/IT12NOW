<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockInController; 
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\UserController; // ← ADDED THIS LINE

// Root redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login routes (guests only)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================
// DASHBOARD - ACCESSIBLE BY ALL ROLES
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ============================================
// INVENTORY & PRODUCTS - Admin & Manager Only
// ============================================
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    
    // ⭐ INVENTORY REPORTS
    Route::get('/inventory/reports', [InventoryReportController::class, 'index'])
        ->name('inventory.reports');
    Route::get('/inventory/reports/data', [InventoryReportController::class, 'getData'])
        ->name('inventory.reports.data');
    Route::get('/inventory/reports/export', [InventoryReportController::class, 'export'])
        ->name('inventory.reports.export');

    // Inventory / Products
    Route::resource('inventory', InventoryController::class);
    Route::resource('products', ProductController::class);

    // Stock Ins
    Route::resource('stockins', StockInController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);
});

// ============================================
// CUSTOMERS & SALES - Admin & Cashier Only
// ============================================
Route::middleware(['auth', 'role:admin,cashier'])->group(function () {
    
    // Customers
    Route::resource('customers', CustomerController::class);

    // Sales Transactions
    Route::resource('sales', SalesTransactionController::class);
    Route::put('sales/{sale}/paid', [SalesTransactionController::class, 'markPaid'])->name('sales.markPaid');
    Route::put('/sales/{sale}/markPaid', [SalesTransactionController::class, 'markPaid']);
    Route::get('/sales/{sale}/print', [SalesTransactionController::class, 'printReceipt'])->name('sales.printReceipt');
});

// ============================================
// USER MANAGEMENT - Admin Only
// ============================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // View all users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    
    // Create Cashier
    Route::get('/users/create-cashier', [UserController::class, 'createCashier'])->name('users.create-cashier');
    Route::post('/users/create-cashier', [UserController::class, 'storeCashier'])->name('users.store-cashier');
    
    // Create Manager
    Route::get('/users/create-manager', [UserController::class, 'createManager'])->name('users.create-manager');
    Route::post('/users/create-manager', [UserController::class, 'storeManager'])->name('users.store-manager');
    
    // List Cashiers and Managers
    Route::get('/users/cashiers', [UserController::class, 'listCashiers'])->name('users.list-cashiers');
    Route::get('/users/managers', [UserController::class, 'listManagers'])->name('users.list-managers');
    
    // Delete User
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});