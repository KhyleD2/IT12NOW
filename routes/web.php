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
use App\Http\Controllers\UserController; 
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SupplierTransactionController;

// Root → login
Route::get('/', fn() => redirect()->route('login'));

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Inventory & Products (Admin + Manager)
Route::middleware(['auth','role:admin,manager'])->group(function () {
    Route::get('/inventory/reports', [InventoryReportController::class, 'index'])->name('inventory.reports');
    Route::get('/inventory/reports/data', [InventoryReportController::class, 'getData'])->name('inventory.reports.data');
    Route::get('/inventory/reports/export', [InventoryReportController::class, 'export'])->name('inventory.reports.export');

    Route::resource('inventory', InventoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('stockins', StockInController::class);
    Route::resource('suppliers', SupplierController::class);
});

// Customers & Sales (Admin + Cashier)
Route::middleware(['auth','role:admin,cashier'])->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('sales', SalesTransactionController::class);

    Route::put('sales/{sale}/paid', [SalesTransactionController::class, 'markPaid'])
        ->name('sales.markPaid');
    Route::get('/sales/{sale}/print', [SalesTransactionController::class, 'printReceipt'])
        ->name('sales.printReceipt');
});

// Users & Employees (Admin only)
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create-cashier', [UserController::class, 'createCashier'])->name('users.create-cashier');
    Route::post('/users/create-cashier', [UserController::class, 'storeCashier'])->name('users.store-cashier');
    Route::get('/users/create-manager', [UserController::class, 'createManager'])->name('users.create-manager');
    Route::post('/users/create-manager', [UserController::class, 'storeManager'])->name('users.store-manager');
    Route::get('/users/cashiers', [UserController::class, 'listCashiers'])->name('users.list-cashiers');
    Route::get('/users/managers', [UserController::class, 'listManagers'])->name('users.list-managers');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');

    // Supplier Transactions
    Route::get('/supplier-transactions', [SupplierTransactionController::class, 'index'])
        ->name('supplier.transactions');
    Route::put('/supplier-transactions/{supplier_transaction}/pay', [SupplierTransactionController::class, 'pay'])
        ->name('supplier-transactions.pay');
    Route::get('/supplier-transactions/{supplier_transaction}/receipt', [SupplierTransactionController::class, 'printReceipt'])
        ->name('supplier-transactions.receipt');
    Route::resource('supplier-transactions', SupplierTransactionController::class)->except(['index']);
});
