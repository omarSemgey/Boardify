<?php

use App\Domains\Users\Controllers\Auth\UserAuthController;


Route::prefix('auth')->group(function () {
    // Public routes
    
    Route::middleware('ensure.guest')->group(function () {
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('register', [UserAuthController::class, 'register']);
    });
    
    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [UserAuthController::class, 'me']);
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::post('refresh', [UserAuthController::class, 'refresh']);
    });
});