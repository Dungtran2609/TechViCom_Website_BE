<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ShippingController;

// ✅ Kiểm tra kết nối API
Route::get('/ping', function (): JsonResponse {
    return response()->json(['message' => 'pong']);
});

// ======================
// 🔒 ADMIN ROUTES
// ======================
Route::prefix('admin/orders')->group(function () {
    // Đơn hàng đã xoá (thùng rác)
    Route::get('/trashed', [OrderApiController::class, 'trashed']);
    Route::post('/{id}/restore', [OrderApiController::class, 'restore']);
    Route::delete('/{id}/force-delete', [OrderApiController::class, 'forceDelete']);

    // Cập nhật trạng thái đơn hàng
    Route::post('/{id}/update-status', [OrderApiController::class, 'updateOrderStatus']);

    // Xử lý trả hàng
    Route::get('/returns', [OrderApiController::class, 'returnsIndex']);
    Route::post('/returns/{id}/process', [OrderApiController::class, 'processReturn']);

    // Quản lý đơn hàng
    Route::get('/', [OrderApiController::class, 'index']);
    Route::get('/{order}', [OrderApiController::class, 'show']);
    Route::put('/{order}', [OrderApiController::class, 'update']);
    Route::delete('/{order}', [OrderApiController::class, 'destroy']);
});

// ======================
// 🚚 TÍNH PHÍ VẬN CHUYỂN
// ======================
Route::post('/shipping-fee/{orderId}', [ShippingController::class, 'calculateShipping']);

// ======================
// 👤 KHÁCH HÀNG ROUTES
// ======================
Route::middleware('auth:sanctum')->group(function () {
    // 📦 Lấy danh sách đơn hàng của khách hàng
    Route::get('/user/orders', [OrderApiController::class, 'apiUserOrders']);

    // 📦 Chi tiết đơn hàng
    Route::get('/user/orders/{id}', [OrderApiController::class, 'apiShow']);

    // 🛒 Tạo đơn hàng mới
    Route::post('/user/orders', [OrderApiController::class, 'apiStore']);

    // 🔁 Gửi yêu cầu trả hàng
    Route::post('/user/orders/{id}/return', [OrderApiController::class, 'returnOrder']);
});