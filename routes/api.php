<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\ShippingController;

Route::prefix('v1')->group(function () {
    // ✅ Xác thực người dùng
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // 🔐 Các route yêu cầu đã đăng nhập
    Route::middleware('auth:sanctum')->group(function () {

        /**
         * 👤 Thông tin người dùng
         */
        Route::get('me', [UserController::class, 'me']);
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::put('users/{id}', [UserController::class, 'update']);

        /**
         * 🔓 Đăng xuất
         */
        Route::post('logout', [AuthController::class, 'logout']);

        /**
         * 📦 Quản lý đơn hàng cho người dùng
         */
        Route::prefix('user')->group(function () {
            Route::get('orders', [OrderApiController::class, 'apiUserOrders']);
            Route::get('orders/{id}', [OrderApiController::class, 'apiShow']);
            Route::post('orders', [OrderApiController::class, 'apiStore']);
            Route::post('orders/{id}/return', [OrderApiController::class, 'returnOrder']);
        });
    });

    /**
     * 🔧 Quản trị đơn hàng (ADMIN)
     */
    Route::prefix('order')->group(function () {
        Route::get('/trashed', [OrderApiController::class, 'trashed']);
        Route::post('/{id}/restore', [OrderApiController::class, 'restore']);
        Route::delete('/{id}/force-delete', [OrderApiController::class, 'forceDelete']);
        Route::post('/{id}/update-status', [OrderApiController::class, 'updateOrderStatus']);

        Route::get('/returns', [OrderApiController::class, 'returnsIndex']);
        Route::post('/returns/{id}/process', [OrderApiController::class, 'processReturn']);

        Route::get('/', [OrderApiController::class, 'index']);
        Route::get('/{order}', [OrderApiController::class, 'show']);
        Route::put('/{order}', [OrderApiController::class, 'update']);
        Route::delete('/{order}', [OrderApiController::class, 'destroy']);
    });

    // 🚚 Tính phí vận chuyển (chưa bật)
    // Route::post('/shipping-fee/{orderId}', [ShippingController::class, 'calculateShipping']);
});
