<?php

use App\Http\Controllers\Api\CMS\ProductControllerV2;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    // Product Routes (Admin có toàn quyền)
    Route::get('/', [ProductControllerV2::class, 'index'])->name('cms.products.index');
    Route::get('/{id}', [ProductControllerV2::class, 'show'])->name('cms.products.show');
    Route::post('/create', [ProductControllerV2::class, 'store'])->name('cms.products.store');
    Route::put('/update/{id}', [ProductControllerV2::class, 'update'])->name('cms.products.update');
    Route::delete('/delete/{id}', [ProductControllerV2::class, 'destroy'])->name('cms.products.destroy');
    Route::post('/import-excel', [ProductControllerV2::class, 'import'])->name('cms.products.import');
    // Route::apiResource('/', ProductControllerV2::class);
});
