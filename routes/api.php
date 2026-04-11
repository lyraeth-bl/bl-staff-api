<?php

use App\Http\Controllers\Api\AppConfiguration\AppConfigurationController;
use App\Http\Controllers\Api\Attendance\AttendanceController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public route.
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });
    Route::get('app-config', [AppConfigurationController::class, 'getAppConfiguration']);

    // Private route.
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
        });

        Route::prefix('profile')->group(function () {
            Route::get('me', [ProfileController::class, 'me']);
            Route::patch('name', [ProfileController::class, 'changeName']);
            Route::patch('password', [ProfileController::class, 'changePassword']);
        });

        Route::prefix('attendance')->group(function () {
            Route::get('monthly', [AttendanceController::class, 'getMonthlyAttendance']);
            Route::get('today', [AttendanceController::class, 'getTodayAttendance']);
            Route::post('check-in', [AttendanceController::class, 'checkIn']);
            Route::post('check-out', [AttendanceController::class, 'checkOut']);
        });
    });
});
