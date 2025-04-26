<?php

use App\Http\Controllers\Api\CMS\UserControllerV2;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/', [UserControllerV2::class, 'index'])->name('cms.users.index');
    Route::get('/{id}', [UserControllerV2::class, 'show'])->name('cms.users.show');
    Route::put('/update/{id}', [UserControllerV2::class, 'update'])->name('cms.users.update');

});
