<?php

use App\Http\Controllers\Api\Cms\OrderControllerV2;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
  // Product Routes (Admin có toàn quyền)
  Route::get('/', [OrderControllerV2::class, 'index'])->name('cms.order.index');
  Route::get('/show/{id}', [OrderControllerV2::class, 'getOrderDetails'])->name('cms.order.orderDetails');
  Route::post('/create', [OrderControllerV2::class, 'store'])->name('cms.order.store');
  Route::put('/update/{id}', [OrderControllerV2::class, 'update'])->name('cms.products.update');
  Route::delete('/delete/{id}', [OrderControllerV2::class, 'destroy'])->name('cms.products.destroy');
  // Route::apiResource('/', OrderControllerV2::class);
});
