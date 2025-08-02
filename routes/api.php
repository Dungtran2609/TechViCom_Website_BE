<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\UserController;

Route::prefix('v1')->group(function () {

    // Nhóm xác thực
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Nhóm dữ liệu công khai
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::get('categories/{category}', [CategoryApiController::class, 'show']);

    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{product}', [ProductApiController::class, 'show']);

    Route::get('news', [NewsController::class, 'index']);
    Route::get('news/{news}', [NewsController::class, 'show']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [UserController::class, 'me']);

        // Quản lý đơn hàng cá nhân (của chính người dùng)
        Route::prefix('my-orders')->as('my-orders.')->group(function () {
            Route::get('/', [OrderApiController::class, 'apiUserOrders'])->name('index');
            Route::post('/', [OrderApiController::class, 'apiStore'])->name('store');
            Route::get('/{order}', [OrderApiController::class, 'apiShow'])->name('show');
            Route::post('/{order}/return', [OrderApiController::class, 'returnOrder'])->name('return');
        });
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
