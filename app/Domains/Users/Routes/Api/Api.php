<?php

use App\Domains\Users\Controllers\AuthController;
use App\Domains\Users\Controllers\UserController;

Route::prefix('users')->group(function (): void {
    Route::post('store', [UserController::class, 'store']);
});



