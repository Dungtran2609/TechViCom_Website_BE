<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;

Route::prefix('v1')->group(function () {
    // Đăng ký, đăng nhập
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Các route cần xác thực
    Route::middleware('auth:sanctum')->group(function () {
        // Thông tin tài khoản đang đăng nhập
        Route::get('me', [UserController::class, 'me']);

        // Đăng xuất
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
