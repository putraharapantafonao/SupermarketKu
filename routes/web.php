<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;

//  KODE BARU: Langsung alihkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    |*/
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    |*/
    Route::resource('categories', CategoryController::class)
        ->middleware('role:Owner,Admin,Gudang');
    Route::resource('products', ProductController::class)
        ->middleware('role:Owner,Admin,Gudang');
    Route::resource('suppliers', SupplierController::class)
        ->middleware('role:Owner,Admin,Gudang');
    Route::resource('customers', CustomerController::class)
        ->middleware('role:Owner,Admin,Kasir');

    Route::middleware('role:Owner,Admin,Kasir')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | POS / Kasir
        |--------------------------------------------------------------------------
        |*/
        Route::get('/pos', [PosController::class, 'index'])
            ->name('pos.index');

        Route::post('/pos/add', [PosController::class, 'addToCart'])
            ->name('pos.add');

        Route::post('/pos/remove', [PosController::class, 'removeFromCart'])
            ->name('pos.remove');

        Route::post('/pos/increase', [PosController::class, 'increaseQuantity'])
            ->name('pos.increase');

        Route::post('/pos/decrease', [PosController::class, 'decreaseQuantity'])
            ->name('pos.decrease');

        Route::post('/pos/clear', [PosController::class, 'clearCart'])
            ->name('pos.clear');

        Route::post('/pos/scan', [PosController::class, 'scanBarcode'])
            ->name('pos.scan');

        /*
        |--------------------------------------------------------------------------
        | Checkout
        |--------------------------------------------------------------------------
        |*/
        Route::get('/pos/checkout', [PosController::class, 'checkout'])
            ->name('pos.checkout');

        Route::post('/pos/checkout', [PosController::class, 'processCheckout'])
            ->name('pos.processCheckout');
    });

    Route::middleware('role:Owner,Admin,Kasir')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Transaksi
        |--------------------------------------------------------------------------
        |*/
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        // SOLUSI ERROR: Menambahkan handler POST untuk memproses simpan checkout POS ke database
        Route::post('/transactions', [TransactionController::class, 'store'])
            ->name('transactions.store');

        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
            ->name('transactions.show');

        Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])
            ->name('transactions.receipt');
    });

    Route::middleware('role:Owner,Admin')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        |*/
        Route::get('/reports/sales', [ReportController::class, 'sales'])
            ->name('reports.sales');

        Route::get('/reports/sales/pdf', [ReportController::class, 'salesPdf'])
            ->name('reports.sales.pdf');

        Route::get('/reports/sales/excel', [ReportController::class, 'salesExcel'])
            ->name('reports.sales.excel');
    });
});

/*
|--------------------------------------------------------------------------
| Logistik, Hak Akses Khusus & Eksternal Middleware Group
|--------------------------------------------------------------------------
|*/
Route::middleware(['auth', 'role:Owner,Admin,Gudang'])->group(function () {
    Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
    Route::post('/stock-opname/update', [StockOpnameController::class, 'update'])->name('stock-opname.update');
});

Route::get('/notifications', [DashboardController::class, 'notifications'])
    ->middleware(['auth'])
    ->name('notifications.index');

Route::resource('promotions', PromotionController::class)
    ->middleware(['auth', 'role:Owner,Admin']);

Route::resource('purchases', PurchaseController::class)
    ->middleware(['auth', 'role:Owner,Admin,Gudang']);

Route::get('/stock-movements', [StockMovementController::class, 'index'])
    ->middleware(['auth', 'role:Owner,Admin,Gudang'])
    ->name('stock-movements.index');

Route::resource('users', UserController::class)
    ->middleware(['auth', 'role:Owner,Admin']);

require __DIR__.'/auth.php';
