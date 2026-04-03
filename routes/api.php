<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Auth routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
        });

        Route::prefix('profile')->group(function () {
            Route::get('me', [ProfileController::class, 'me']);
            Route::patch('name', [ProfileController::class, 'changeName']);
            Route::patch('password', [ProfileController::class, 'changePassword']);
        });
    });
});
