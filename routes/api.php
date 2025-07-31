<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\ShippingController;
Route::prefix('v1')->group(function () {
    
    
    
    Route::prefix('order')->group(function () {
        // Đơn hàng đã xoá
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
    
    
    
    

    
    
    
});