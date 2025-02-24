<?php

use App\Http\Controllers\Api\Cms\ProductController;
use App\Http\Controllers\Api\Cms\CategoryController;
use App\Http\Controllers\Api\App\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//5|dRp0ydTptgViSlXoh15Fg8sRI3LXw52GrdJZ70WC7cba76ab user
//6|7zq1YAeqxbuZFwaqEy8MrireevoPHxxJh1PDJ00K61ffc6d7 admin
//8|GHo19AMbXD9SezbrdZipuq7IDmODtOf4nuaeYm3Hf81a042d user05

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
