<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {

        Route::post('login', [AuthController::class, 'login'])
            ->name('login')
            ->middleware('throttle:api-login');

        Route::post('refresh', [AuthController::class, 'refresh'])
            ->name('refresh')
            ->middleware('throttle:api-login');

        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [UserController::class, 'me'])->name('me');
    });
});
