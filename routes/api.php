<?php

use App\Http\Controllers\Api\Cms\ProductController;
use App\Http\Controllers\Api\App\AuthController;
use App\Http\Controllers\Api\Cms\OrderControllerV2;
use App\Http\Controllers\Api\App\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//5|dRp0ydTptgViSlXoh15Fg8sRI3LXw52GrdJZ70WC7cba76ab user
//6|7zq1YAeqxbuZFwaqEy8MrireevoPHxxJh1PDJ00K61ffc6d7 admin

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::middleware('auth:sanctum')->post('/app/orders/create', [OrderController::class, 'store'])
    ->name('order.store');

Route::prefix('cms')->group(function () {
    Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
        \App\Helpers\RouteHelper::includeRouteFiles(__DIR__ . '/cms');
    });
});
