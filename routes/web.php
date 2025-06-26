<?php

use App\Http\Controllers\Admin\BaiViet\NewsCategoryController;
use App\Http\Controllers\Admin\BaiViet\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Lienhe\LienHeAdminController;


// Client routes
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\lienhe\LienHeController;
use App\Http\Controllers\Client\TaikhoanUser\UserProfileController;

// Chuyển hướng trang chủ
Route::prefix('/')->name('client.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('lienhe', LienHeController::class);

    // Tài khoản người dùng
    Route::controller(UserProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('profile.index');
        Route::get('profile/edit', 'edit')->name('profile.edit');
        Route::post('profile/update', 'update')->name('profile.update');
    });
});


// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories routes
    Route::get('products/categories/trashed', [CategoryController::class, 'trashed'])->name('products.categories.trashed');
    Route::post('products/categories/{id}/restore', [CategoryController::class, 'restore'])->name('products.categories.restore');
    Route::delete('products/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('products.categories.force-delete');
    Route::resource('products/categories', CategoryController::class)->names('products.categories');

    // Quản lý bài viết
    Route::resource('news', NewsController::class);
    Route::resource('news-categories', NewsCategoryController::class);


    // Quản lý liên hệ
    Route::get('lien-he', [LienHeAdminController::class, 'index'])->name('lienhe.index');
    Route::get('lien-he/{id}', [LienHeAdminController::class, 'show'])->name('lienhe.show');
    Route::delete('lien-he/{id}', [LienHeAdminController::class, 'destroy'])->name('lienhe.destroy');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
