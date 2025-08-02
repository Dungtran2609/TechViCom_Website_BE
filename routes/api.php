<?php
// File: routes/api.php (Bản hoàn chỉnh đã sửa lỗi)

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\ShippingController;
// ✅ Đã thêm: Import các Controller còn thiếu
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\CartController;
Route::prefix('v1')->group(function () {
    // === CÁC ROUTE XÁC THỰC (CÔNG KHAI) ===
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // === CÁC ROUTE DỮ LIỆU (CÔNG KHAI) ===
    // Ai cũng có thể xem sản phẩm, tin tức, danh mục... mà không cần đăng nhập
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{product}', [ProductApiController::class, 'show']); // Sử dụng {product} để route model binding
    Route::get('news', [NewsController::class, 'index']);
    Route::get('news/{news}', [NewsController::class, 'show']); // Sử dụng {news} để route model binding
    // 📦 Sản phẩm (public, không cần đăng nhập)
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{id}', [ProductApiController::class, 'show']);
    // categories
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::get('categories/{id}', [CategoryApiController::class, 'show']);
    // Bạn cũng có thể cần các route khác ở đây, ví dụ:
    // Route::get('categories', [CategoryController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {

    /**
     * 👤 Thông tin người dùng
     */
    Route::get('me', [UserController::class, 'me']);
    // Các route quản lý user khác (thường dành cho admin)
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);

    /**
     * 🔓 Đăng xuất
     */
    Route::post('logout', [AuthController::class, 'logout']);

    /**
     * 📦 Quản lý Đơn hàng, Giỏ hàng... của chính người dùng đã đăng nhập
     */
    
    // ✅ SỬA LẠI TẠI ĐÂY: Đổi 'users' thành 'user' (số ít)
    Route::prefix('user')->group(function () {
        // Nhóm tất cả các route liên quan đến 'orders'
        Route::prefix('orders')->group(function () {
            // GET /api/v1/user/orders -> Lấy danh sách đơn hàng của tôi
            Route::get('/', [OrderApiController::class, 'apiUserOrders']);
            // GET /api/v1/user/orders/{order} -> Xem chi tiết 1 đơn hàng của tôi
            Route::get('/{order}', [OrderApiController::class, 'apiShow']);
            // POST /api/v1/user/orders/store -> Tạo 1 đơn hàng mới
            Route::post('store', [OrderApiController::class, 'store']);
            // POST /api/v1/user/orders/{id}/return -> Yêu cầu trả hàng
            Route::post('{id}/return', [OrderApiController::class, 'returnsIndex']);
        });


    });

});
});