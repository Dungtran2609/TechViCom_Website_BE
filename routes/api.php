<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\OrderApiController;

use App\Http\Controllers\Api\V1\NewsApiController;
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\UserController;


Route::prefix('v1')->group(function () {

    // Nhóm xác thực
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // 📰 News API (public)
    Route::prefix('news')->group(function () {
        Route::get('/', [NewsApiController::class, 'index']);
        Route::get('/featured', [NewsApiController::class, 'featured']);
        Route::get('/{id}', [NewsApiController::class, 'show']);
        Route::get('/{id}/comments', [NewsApiController::class, 'comments']);
    });

    // 📂 News Categories API (public)
    Route::get('news-categories', [NewsApiController::class, 'categories']);
    Route::get('news-categories/{categoryId}/news', [NewsApiController::class, 'newsByCategory']);

    // 🔐 Các route cần xác thực
    // Nhóm dữ liệu công khai
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::get('categories/{category}', [CategoryApiController::class, 'show']);

    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{product}', [ProductApiController::class, 'show']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [UserController::class, 'me']);

        // 🔓 Đăng xuất
        Route::post('logout', [AuthController::class, 'logout']);

        // 📰 News API (cần auth)
        Route::post('/news', [NewsApiController::class, 'store']);
        Route::put('/news/{news}', [NewsApiController::class, 'update']);
        Route::delete('/news/{news}', [NewsApiController::class, 'destroy']);

        // Tương tác với Tin tức (bất kỳ user nào đã đăng nhập)
        Route::post('/news/{news}/comments', [NewsApiController::class, 'addComment']);
        Route::post('/news/{news}/like', [NewsApiController::class, 'toggleLikePost']);
        Route::post('/comments/{comment}/like', [NewsApiController::class, 'toggleLikeComment']);

        // 📦 Quản lý đơn hàng cho người dùng
        Route::get('user/orders', [OrderApiController::class, 'apiUserOrders']);
        Route::get('user/orders/{id}', [OrderApiController::class, 'apiShow']);
        Route::post('user/orders', [OrderApiController::class, 'apiStore']);
        Route::post('user/orders/{id}/return', [OrderApiController::class, 'returnOrder']);
    });

    // Middleware 'role:admin,staff' sẽ chỉ cho phép những người dùng có vai trò là
    // 'admin' hoặc 'staff' đi qua.
    Route::prefix('admin')->as('admin.')->middleware(['auth:sanctum', 'role:admin,staff'])->group(function () {
        // Quản lý tất cả người dùng
        // Sử dụng apiResource để tạo các route index, store, show, update, destroy
        Route::apiResource('users', UserController::class);

        // Quản lý tất cả đơn hàng
        Route::prefix('orders')->as('orders.')->group(function () {
            Route::get('/', [OrderApiController::class, 'index'])->name('index');
            Route::post('/', [OrderApiController::class, 'store'])->name('store');
            Route::get('/{order}', [OrderApiController::class, 'show'])->name('show');
            Route::put('/{order}', [OrderApiController::class, 'update'])->name('update');
            Route::delete('/{order}', [OrderApiController::class, 'destroy'])->name('destroy');
        });

        // Bạn có thể thêm các route quản trị khác ở đây
    });
});
