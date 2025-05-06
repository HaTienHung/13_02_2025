<?php


use App\Http\Controllers\Api\App\CategoryController;
use App\Http\Controllers\Api\App\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Products
Route::middleware(['middleware' => 'language'])->group(function () {
    Route::get('/', [ProductController::class, 'getLastestProducts'])->name('products.latest');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'showBySlug'])->name('products.show.slug');
    Route::get('/products/id/{id}', [ProductController::class, 'show'])->name('products.show.id');
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{slug}', [CategoryController::class, 'getProductsByCategorySlug'])->name('categories.slug.products');
});

Route::prefix('app')->group(function () {
    Route::group(['middleware' => ['auth:sanctum', 'role:user', 'language']], function () {
        \App\Helpers\RouteHelper::includeRouteFiles(__DIR__ . '/app');
    });
});

Route::prefix('cms')->group(function () {
    Route::group(['middleware' => ['auth:sanctum', 'role:admin', 'language']], function () {
        \App\Helpers\RouteHelper::includeRouteFiles(__DIR__ . '/cms');
    });
});
