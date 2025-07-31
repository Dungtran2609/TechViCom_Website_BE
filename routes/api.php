<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\ShippingController;

Route::prefix('v1')->group(function () {
    // ✅ Đăng ký & Đăng nhập
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // 🔐 Các route cần xác thực
    Route::middleware('auth:sanctum')->group(function () {
        // 👤 Thông tin người dùng
        Route::get('me', [UserController::class, 'me']);

        // 🔓 Đăng xuất
        Route::post('logout', [AuthController::class, 'logout']);

        // 📦 Quản lý đơn hàng cho người dùng
        Route::get('user/orders', [OrderApiController::class, 'apiUserOrders']);
        Route::get('user/orders/{id}', [OrderApiController::class, 'apiShow']);
        Route::post('user/orders', [OrderApiController::class, 'apiStore']);
        Route::post('user/orders/{id}/return', [OrderApiController::class, 'returnOrder']);
    });

    // 🔧 Quản trị đơn hàng (ADMIN)
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

    // 🚚 Tính phí vận chuyển
    // Route::post('/shipping-fee/{orderId}', [ShippingController::class, 'calculateShipping']);
});
