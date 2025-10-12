<?php

use App\Domains\Users\Controllers\Crud\UserCrudController;


Route::prefix('users')->group(function () {
    // Public routes
    
    Route::middleware('ensure.guest')->group(function () {
    });
    
    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::post('update', [UserCrudController::class, 'update']);
        Route::delete('destroy', [UserCrudController::class, 'destroy']);
    });
});