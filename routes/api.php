<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;

Route::prefix('v1')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    // Lấy thông tin user đang đăng nhập
    Route::middleware('auth:sanctum')->get('me', function (\Illuminate\Http\Request $request) {
        return response()->json($request->user());
    });
});;
