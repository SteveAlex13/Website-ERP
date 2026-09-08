<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\BankReportController;
use App\Http\Controllers\CashExpenseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DailyFinancialReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\ExpensePurchaseController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalePaymentController;
use App\Http\Controllers\SaleRefundController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware('role:superadmin')->group(function () {
        Route::post('/profile/quotation-delete-key', [ProfileController::class, 'generateQuotationDeleteKey'])->name('profile.quotation-delete-key.generate');
        Route::get('user-roles', [UserRoleController::class, 'index'])->name('user-roles.index');
        Route::patch('user-roles/{user}', [UserRoleController::class, 'update'])->name('user-roles.update');
    });
    Route::resource('products', ProductController::class)->only(['index']);
    Route::middleware('role:admin,purchasing,warehouse')->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
    });

    Route::middleware('role:admin,purchasing,finance')->group(function () {
        Route::resource('expense-purchases', ExpensePurchaseController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    });
    Route::middleware('role:admin,purchasing,sales,finance')->group(function () {
        Route::resource('contacts', ContactController::class)->except(['show']);
    });

    Route::middleware('role:admin,purchasing,warehouse')->group(function () {
        Route::resource('purchases', PurchaseController::class);
        Route::post('purchases/{purchase}/receive', GoodsReceiptController::class)->name('purchases.receive');
    });

    Route::resource('sales', SaleController::class);
    Route::post('sales/{sale}/payment', SalePaymentController::class)->name('sales.payment');
    Route::post('sales/{sale}/refund', SaleRefundController::class)->name('sales.refund');
    Route::middleware('role:admin,finance,warehouse')->group(function () {
        Route::resource('product-returns', ProductReturnController::class)->only(['index', 'create', 'store']);
    });
    Route::post('sales/{sale}/deliveries', [DeliveryOrderController::class, 'store'])
        ->middleware('role:admin,warehouse')
        ->name('sales.deliveries.store');
    Route::get('sales/{sale}/invoice/print', [SaleController::class, 'printInvoice'])->name('sales.invoice.print');
    Route::get('delivery-orders/{deliveryOrder}/print', [DeliveryOrderController::class, 'print'])->name('delivery_orders.print');

    Route::middleware('role:admin,finance')->group(function () {
        Route::resource('cash-expenses', CashExpenseController::class)->except(['show']);
        Route::resource('assets', AssetController::class)->except(['show']);
        Route::get('reports', ReportController::class)->name('reports.index');
        Route::get('reports/bank', BankReportController::class)->name('reports.bank');
        Route::get('reports/daily-financial', DailyFinancialReportController::class)->name('reports.daily-financial');
    });
});

require __DIR__.'/auth.php';
