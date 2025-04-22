<?php

use App\Http\Controllers\Api\Cms\CategoryControllerV2;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    // Product Routes (Admin có toàn quyền)
    Route::get('/', [CategoryControllerV2::class, 'index'])->name('cms.categories.index');
    Route::get('/{id}', [CategoryControllerV2::class, 'getCategoryById'])->name('cms.categories.detail');
    Route::post('/create', [CategoryControllerV2::class, 'store'])->name('cms.category.store');
    Route::put('/update/{id}', [CategoryControllerV2::class, 'update'])->name('cms.category.update');
    Route::delete('/delete/{id}', [CategoryControllerV2::class, 'destroy'])->name('cms.category.destroy');
    // Route::apiResource('/', OrderControllerV2::class);
});
