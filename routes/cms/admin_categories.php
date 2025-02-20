<?php

use App\Http\Controllers\Api\Cms\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
  // Product Routes (Admin có toàn quyền)
  Route::post('/create', [CategoryController::class, 'store'])->name('cms.category.store');
  Route::put('/update/{id}', [CategoryController::class, 'update'])->name('cms.category.update');
  Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('cms.category.destroy');
  // Route::apiResource('/', OrderControllerV2::class);
});
