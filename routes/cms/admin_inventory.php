<?php

use App\Http\Controllers\Api\CMS\InventoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventories')->group(function () {
    // Product Routes (Admin có toàn quyền)
    Route::post('/create', [InventoryController::class, 'store'])->name('cms.inventory.store');
    Route::get('/show/{id}', [InventoryController::class, 'show'])->name('cms.inventory.show');
    Route::get('/', [InventoryController::class, 'getStockReport'])->name('cms.inventory.stockReport');
    Route::get('/show/{id}/transactions', [InventoryController::class, 'showInventoryRecords'])->name('cms.inventory.inventoryRecords');
    // Route::put('/update/{id}', [CategoryControllerV2::class, 'update'])->name('cms.category.update');
    // Route::delete('/delete/{id}', [CategoryControllerV2::class, 'destroy'])->name('cms.category.destroy');
    // Route::apiResource('/', OrderControllerV2::class);
});
