<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\VisualInventoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\TransactionController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

// Product CRUD
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Product Variant CRUD
Route::post('/variants', [ProductVariantController::class, 'store'])->name('variants.store');
Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');

// Transaction CRUD
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

// Customers CRUD
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

// Production Orders CRUD
Route::get('/orders', [ProductionOrderController::class, 'index'])->name('orders.index');
Route::post('/orders', [ProductionOrderController::class, 'store'])->name('orders.store');
Route::put('/orders/{order}', [ProductionOrderController::class, 'update'])->name('orders.update');
Route::post('/orders/{order}/status', [ProductionOrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::delete('/orders/{order}', [ProductionOrderController::class, 'destroy'])->name('orders.destroy');

// Suppliers CRUD
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

// Visual Inventory Map
Route::get('/visual-inventory', [VisualInventoryController::class, 'index'])->name('visual-inventory.index');
Route::get('/visual-inventory/{zone}', [VisualInventoryController::class, 'showContainer'])->name('visual-inventory.container');
Route::post('/api/visual-inventory/zones', [VisualInventoryController::class, 'storeZone']);
Route::put('/api/visual-inventory/zones/{zone}', [VisualInventoryController::class, 'updateZone']);
Route::delete('/api/visual-inventory/zones/{zone}', [VisualInventoryController::class, 'destroyZone']);
Route::post('/api/visual-inventory/zones/{zone}/move', [VisualInventoryController::class, 'moveZone']);
