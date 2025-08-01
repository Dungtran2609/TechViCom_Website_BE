<?php
// File: routes/api.php (Bản hoàn chỉnh đã sửa lỗi)

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\ShippingController;
// ✅ Đã thêm: Import các Controller còn thiếu
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\NewsController;

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

    // Bạn cũng có thể cần các route khác ở đây, ví dụ:
    // Route::get('categories', [CategoryController::class, 'index']);


    // === CÁC ROUTE YÊU CẦU ĐÃ ĐĂNG NHẬP (BẢO MẬT) ===
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

        // Bạn có thể thêm các route yêu cầu đăng nhập khác ở đây
        // Ví dụ: Viết bình luận, đánh giá sản phẩm...
    });


    // === CÁC ROUTE DÀNH CHO QUẢN TRỊ VIÊN (ADMIN) ===
    // Bạn nên thêm một middleware nữa ở đây để kiểm tra vai trò 'admin'
    Route::prefix('order')->middleware(['auth:sanctum'/*, 'role:admin'*/])->group(function () {
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
