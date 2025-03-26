<?php

use App\Http\Controllers\Api\App\AuthController;
use App\Http\Controllers\Api\App\CategoryController;
use App\Http\Controllers\Api\App\ProductController;
use App\Http\Controllers\Api\App\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//1|gLkOo8cl7vgB5xQpGVJhQ0VNrnQrMlsiWEvRsyMA93ca5f7f user01
//13|H8Lwm86Lu77FKIAqx4i2pcueoCsSHIEp3pa7l2Smebd561da user05
//12|jv7s2YfDkDnztqmwFYtwymYxyym46RMdfQiPMmbm4d708bbe admin01

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
