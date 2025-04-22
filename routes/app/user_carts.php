<?php

use App\Http\Controllers\Api\App\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'getCart'])->name('app.cart.get');
    Route::post('/store', [CartController::class, 'addToCart'])->name('app.cart.add');
    Route::put('/update', [CartController::class, 'updateCart'])->name('app.cart.update');
    Route::delete('/delete', [CartController::class, 'multipleDelete'])->name('app.cart.multipleDelete');
    Route::delete('/delete/{id}', [CartController::class, 'destroy'])->name('app.cart.delete');
    Route::delete('/clear', [CartController::class, 'clearCart'])->name('app.cart.clear');
    Route::post('/checkout', [CartController::class, 'checkOut'])->name('app.cart.checkOut');
});
