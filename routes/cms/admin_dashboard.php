<?php

use App\Http\Controllers\Api\CMS\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::get('', [DashboardController::class, 'stats'])->name('cms.dashboard.stats');
    Route::get('/latest-invoices', [DashboardController::class, 'latestInvoices'])->name('cms.dashboard.latestInvoices');
    Route::get('/revenue', [DashboardController::class, 'getRevenueByDay'])->name('cms.dashboard.getRevenueByDay');
});
