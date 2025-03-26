<?php

use App\Http\Controllers\Api\Cms\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('cms.auth.logout');
    Route::post('/login', [AuthController::class, 'login'])->name('cms.users.login')->withoutMiddleware(['role:admin', 'auth:sanctum']);
});
