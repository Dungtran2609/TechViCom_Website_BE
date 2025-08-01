<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\ShippingController;

Route::prefix('v1')->group(function () {
    // 📦 Biến thể sản phẩm (public, không cần đăng nhập)
    Route::get('product-variants', [\App\Http\Controllers\Api\V1\ProductVariantApiController::class, 'index']);
    Route::get('product-variants/{id}', [\App\Http\Controllers\Api\V1\ProductVariantApiController::class, 'show']);
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

    // 📦 Sản phẩm (public, không cần đăng nhập)
    Route::get('products', [\App\Http\Controllers\Api\V1\ProductApiController::class, 'index']);
    Route::get('products/{id}', [\App\Http\Controllers\Api\V1\ProductApiController::class, 'show']);
    // categories
    Route::get('categories', [\App\Http\Controllers\Api\V1\CategoryApiController::class, 'index']);
    Route::get('categories/{id}', [\App\Http\Controllers\Api\V1\CategoryApiController::class, 'show']);
    // brands

    // �🚚 Tính phí vận chuyển
    // Route::post('/shipping-fee/{orderId}', [ShippingController::class, 'calculateShipping']);

    // 📢 Banner (public)
    Route::get('banners', [\App\Http\Controllers\Api\V1\BannerApiController::class, 'index']);
    Route::get('banners/{id}', [\App\Http\Controllers\Api\V1\BannerApiController::class, 'show']);

    // 📰 News (public)
    Route::get('news', [\App\Http\Controllers\Api\V1\NewsApiController::class, 'index']);
    Route::get('news/{id}', [\App\Http\Controllers\Api\V1\NewsApiController::class, 'show']);

    // 🎟️ Voucher (public)

    Route::get('coupons', [\App\Http\Controllers\Api\V1\CouponApiController::class, 'index']);
    Route::get('coupons/{id}', [\App\Http\Controllers\Api\V1\CouponApiController::class, 'show']);
});
