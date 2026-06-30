<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\OwnerController;


Route::get('/', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        $role = Illuminate\Support\Facades\Auth::user()->role->name ?? '';
        if ($role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'Kasir') {
            return redirect()->route('kasir.dashboard');
        } elseif ($role === 'Owner') { 
            return redirect()->route('owner.dashboard');
        }
    }
    return redirect('/login');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.proses');
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    
    Route::middleware('role:Admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            $categoryCount = \App\Models\Category::count();
            $menuCount = \App\Models\Menu::count();
            $userCount = \App\Models\User::count();
            
            $lowStockMenus = \App\Models\Menu::whereHas('stock', function($q) {
                $q->where('current_stock', '<=', 10);
            })->with('stock')->get();
            
            return view('admin.dashboard', compact('categoryCount', 'menuCount', 'userCount', 'lowStockMenus'));
        })->name('admin.dashboard');
        
        Route::resource('categories', CategoryController::class);
        Route::resource('menus', MenuController::class);
        Route::get('stocks/{stock}/history', [StockController::class, 'history'])->name('stocks.history');
        Route::resource('stocks', StockController::class)->only(['index', 'edit', 'update']);
        Route::resource('tables', TableController::class)->except(['show']);
        Route::resource('users', UserController::class);
    });

    
    Route::middleware('role:Kasir')->prefix('kasir')->group(function () {

        Route::get('/dashboard', function() {
            return redirect()->route('kasir.orders.incoming');
        })->name('kasir.dashboard');

        Route::get('/orders/incoming', [CashierController::class, 'incomingOrders'])->name('kasir.orders.incoming');
        Route::get('/orders/processing', [CashierController::class, 'processingOrders'])->name('kasir.orders.processing');
        Route::get('/orders/ready', [CashierController::class, 'readyOrders'])->name('kasir.orders.ready');
        
        Route::get('/tables', [CashierController::class, 'tables'])->name('kasir.tables.index');
        Route::post('/tables/{id}/toggle', [CashierController::class, 'toggleTableStatus'])->name('kasir.tables.toggle');
        
        // Take Away POS Routes
        Route::get('/order/create', [CashierController::class, 'createOrder'])->name('kasir.order.create');
        Route::post('/order/store', [CashierController::class, 'storeOrder'])->name('kasir.order.store');

        Route::post('/order/{id}/update-status', [CashierController::class, 'updateStatus'])->name('kasir.updateStatus');
        Route::get('/order/{id}/print', [CashierController::class, 'printReceipt'])->name('kasir.printReceipt');
        Route::get('/order/{id}/print-kitchen', [CashierController::class, 'printKitchenReceipt'])->name('kasir.printKitchenReceipt');
        Route::get('/history', [CashierController::class, 'history'])->name('kasir.history');
        Route::get('/end-of-day', [CashierController::class, 'endOfDay'])->name('kasir.endOfDay');
        
        
    });

    Route::middleware('role:Owner')->prefix('owner')->group(function () {
        // Hapus closure yang tidak perlu dan definisikan rute secara langsung
        Route::get('/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard');
        Route::get('/stock', [OwnerController::class, 'stockMonitor'])->name('owner.stock');
        Route::get('/export/excel', [OwnerController::class, 'exportExcel'])->name('owner.exportExcel');
        Route::get('/export/pdf', [OwnerController::class, 'exportPdf'])->name('owner.exportPdf');

        // Laporan Penjualan Lengkap
        Route::get('/reports', [ReportController::class, 'index'])->name('owner.reports.index');
        Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('owner.reports.excel');
        Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('owner.reports.pdf');
    });

});

// ---------------------------------------------------------
// RUTE PELANGGAN (Tidak perlu login / auth)
// ---------------------------------------------------------
Route::get('/order', [CustomerOrderController::class, 'index'])->name('customer.order');

Route::post('/order/checkout', [CustomerOrderController::class, 'checkout'])->name('customer.checkout');
Route::get('/order/status/{code}', [CustomerOrderController::class, 'status'])->name('customer.status');
Route::get('/order/history', [CustomerOrderController::class, 'history'])->name('customer.history');

// Route khusus untuk membersihkan cache di hosting
Route::get('/clear-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "Cache berhasil dibersihkan";
});
