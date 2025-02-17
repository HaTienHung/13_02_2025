<?php

use App\Http\Controllers\Api\Cms\OrderControllerV2;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
  // Product Routes (Admin có toàn quyền)
  Route::get('/', [OrderControllerV2::class, 'index'])->name('cms.order.index');
  Route::post('/create', [OrderControllerV2::class, 'store'])->name('cms.order.store');
  // Route::put('/update/{id}', [ProductController::class, 'update'])->name('cms.products.update');
  // Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('cms.products.destroy');
});
