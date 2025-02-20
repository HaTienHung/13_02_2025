<?php

use App\Http\Controllers\Api\Cms\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('product')->group(function () {
  // Product Routes (Admin có toàn quyền)
  Route::post('/create', [ProductController::class, 'store'])->name('cms.products.store');
  Route::put('/update/{id}', [ProductController::class, 'update'])->name('cms.products.update');
  Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('cms.products.destroy');
  // Route::apiResource('/', ProductController::class);
});
