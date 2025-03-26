<?php

use App\Http\Controllers\Api\App\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('app.user.login')->withoutMiddleware(['auth:sanctum','role:user']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('app.user.logout')->withoutMiddleware(['role:user']);
    Route::post('/register', [AuthController::class, 'createUser'])->name('app.user.register')->withoutMiddleware(['auth:sanctum','role:user']);
});
