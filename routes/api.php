<?php

use App\Http\Controllers\Api\Cms\ProductController;
use App\Http\Controllers\Api\Cms\CategoryController;
use App\Http\Controllers\Api\App\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//1|gLkOo8cl7vgB5xQpGVJhQ0VNrnQrMlsiWEvRsyMA93ca5f7f user01
//2|wSeP9jaolbS6Mz7m41xikZv9g6tHjrJxlc3nzL45641d3d06 user02
//3|RgHU8010356cko8Rho6mNxiLdZSRmlTaTKbT854Q873dad6c admin01

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
//Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
//Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}/products', [CategoryController::class, 'getProductsByCategory'])->name('categories.products');

Route::prefix('app')->group(function () {
    Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
        \App\Helpers\RouteHelper::includeRouteFiles(__DIR__ . '/app');
    });
});

Route::prefix('cms')->group(function () {
    Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
        \App\Helpers\RouteHelper::includeRouteFiles(__DIR__ . '/cms');
    });
});
