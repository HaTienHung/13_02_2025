<?php

use App\Http\Controllers\Api\Cms\InventoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventories')->group(function () {
  // Product Routes (Admin có toàn quyền)
  Route::post('/create', [InventoryController::class, 'store'])->name('cms.inventory.store');
  Route::get('/show/{id}', [InventoryController::class, 'show'])->name('cms.inventory.show');
  Route::get('/show', [InventoryController::class, 'getStockReport'])->name('cms.inventory.stockReport');
  // Route::put('/update/{id}', [CategoryController::class, 'update'])->name('cms.category.update');
  // Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('cms.category.destroy');
  // Route::apiResource('/', OrderControllerV2::class);
});
