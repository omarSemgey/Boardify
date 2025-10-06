<?php

use App\Domains\Users\Controllers\AuthController;


Route::prefix('auth')->group(function () {
    // Public routes
    
    Route::middleware('ensure.guest')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });
    
    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});