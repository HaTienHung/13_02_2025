<?php

use App\Http\Controllers\Api\App\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
  Route::get('/show', [OrderController::class, 'show'])->name('app.order.show');
  Route::post('/create', [OrderController::class, 'store'])->name('app.order.store');
  Route::put('/update/{id}', [OrderController::class, 'update'])->name('app.products.update');
  Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('app.products.destroy');
});
