<?php

use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BaiViet\NewsCategoryController;
use App\Http\Controllers\Admin\BaiViet\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\AttributeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\User\StaffController;
use App\Http\Controllers\Admin\User\AdminController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Order\RefundController;
use App\Http\Controllers\Admin\Post\PostController;
use App\Http\Controllers\Admin\Review\CommentController;
use App\Http\Controllers\Admin\Review\ReplyController;
use App\Http\Controllers\Admin\Promotion\CouponController;
use App\Http\Controllers\Admin\Promotion\ProgramController;
use App\Http\Controllers\Admin\System\BannerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
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

    Route::prefix('products')->name('products.')->group(function () {
            Route::resource('categories', AdminCategoryController::class);
            Route::resource('products', AdminProductController::class);
            Route::resource('brands', AdminBrandController::class);
            Route::resource('attributes', AdminAttributeController::class);
        });
        // Thêm các resource khác nếu cần

    // Quản lý bài viết
    Route::resource('news', NewsController::class);
    Route::resource('news-categories', NewsCategoryController::class);


    // Quản lý liên hệ
    Route::get('lien-he', [LienHeAdminController::class, 'index'])->name('lienhe.index');
    Route::get('lien-he/{id}', [LienHeAdminController::class, 'show'])->name('lienhe.show');
    Route::delete('lien-he/{id}', [LienHeAdminController::class, 'destroy'])->name('lienhe.destroy');


    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});
