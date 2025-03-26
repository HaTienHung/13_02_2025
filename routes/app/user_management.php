<?php

use App\Http\Controllers\Api\App\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/{id}/info', [UserController::class, 'show'])->name('app.user.info');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('app.user.update');
});
