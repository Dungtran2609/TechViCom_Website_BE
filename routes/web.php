<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\Contacts\ContactsAdminController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Admin\News\NewsCategoryController;
use App\Http\Controllers\Admin\News\NewsCommentController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Admin\Products\BrandController;
use App\Http\Controllers\Admin\Products\ProductController;
use App\Http\Controllers\Admin\Products\CategoryController;
use App\Http\Controllers\Admin\Products\AttributeController;
use App\Http\Controllers\Admin\Products\AttributeValueController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\Products\ProductCommentAdminController;
use App\Http\Controllers\Admin\Products\ProductVariantController;


Route::middleware([IsAdmin::class])->prefix('admin-control')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('banner', BannerController::class);
    // Categories
    Route::get('products/categories/trashed', [CategoryController::class, 'trashed'])->name('products.categories.trashed');
    Route::post('products/categories/{id}/restore', [CategoryController::class, 'restore'])->name('products.categories.restore');
    Route::delete('products/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('products.categories.force-delete');
    Route::resource('products/categories', CategoryController::class)->names('products.categories');

    // Brands
    Route::get('products/brands/trashed', [BrandController::class, 'trashed'])->name('products.brands.trashed');
    Route::post('products/brands/{id}/restore', [BrandController::class, 'restore'])->name('products.brands.restore');
    Route::delete('products/brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('products.brands.force-delete');
    Route::resource('products/brands', BrandController::class)->names('products.brands');

    // Attributes
    Route::get('products/attributes/trashed', [AttributeController::class, 'trashed'])->name('products.attributes.trashed');
    Route::post('products/attributes/{id}/restore', [AttributeController::class, 'restore'])->name('products.attributes.restore');
    Route::delete('products/attributes/{id}/force-delete', [AttributeController::class, 'forceDelete'])->name('products.attributes.force-delete');
    Route::resource('products/attributes', AttributeController::class)->names('products.attributes');

    // Attribute Values
    Route::prefix('products/attributes')->name('products.attributes.')->group(function () {
        Route::get('{attribute}/values/trashed', [AttributeValueController::class, 'trashed'])->name('values.trashed');
        Route::post('values/{id}/restore', [AttributeValueController::class, 'restore'])->name('values.restore');
        Route::delete('values/{id}/force-delete', [AttributeValueController::class, 'forceDelete'])->name('values.force-delete');
        Route::get('{attribute}/values', [AttributeValueController::class, 'index'])->name('values.index');
        Route::post('{attribute}/values', [AttributeValueController::class, 'store'])->name('values.store');
        Route::get('values/{value}/edit', [AttributeValueController::class, 'edit'])->name('values.edit');
        Route::put('values/{value}', [AttributeValueController::class, 'update'])->name('values.update');
        Route::delete('values/{value}', [AttributeValueController::class, 'destroy'])->name('values.destroy');
    });

    // Biến thể sản phẩm
    Route::get('products/variants/select', [ProductVariantController::class, 'selectProduct'])->name('products.variants.select');
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('variants/{variant}', [ProductVariantController::class, 'show'])->name('variants.show');
        Route::get('{product}/variants', [ProductVariantController::class, 'index'])->name('variants.index');
        Route::get('{product}/variants/create', [ProductVariantController::class, 'create'])->name('variants.create');
        Route::post('{product}/variants', [ProductVariantController::class, 'store'])->name('variants.store');
        Route::get('variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
        Route::put('variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
        Route::delete('variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
    });

    // Sản phẩm
    Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::resource('products', ProductController::class)->names('products');

    // News
    Route::resource('news', NewsController::class);
    Route::resource('news-categories', NewsCategoryController::class);
    Route::get('/news-comments', [NewsCommentController::class, 'index'])->name('news-comments.index');
    Route::delete('/news-comments/{id}', [NewsCommentController::class, 'destroy'])->name('news-comments.destroy');
    Route::patch('/news-comments/{id}/toggle', [NewsCommentController::class, 'toggleVisibility'])->name('news-comments.toggle');

    // Contacts
    Route::get('contacts', [ContactsAdminController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{id}', [ContactsAdminController::class, 'show'])->name('contacts.show');

    Route::delete('contacts/{id}', [ContactsAdminController::class, 'destroy'])->name('contacts.destroy');

    // Quản lý sản phẩm
    Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::resource('products', ProductController::class)->names('products');
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('trashed', [OrderController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [OrderController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [OrderController::class, 'forceDelete'])->name('force-delete');
        Route::post('{id}/update-status', [OrderController::class, 'updateOrders'])->name('updateOrders');
        Route::get('returns', [OrderController::class, 'returnsIndex'])->name('returns');
        Route::post('returns/{id}/process', [OrderController::class, 'processReturn'])->name('process-return');
        Route::resource('', OrderController::class)->parameters(['' => 'order'])->names('');
    });

    // Quản lý bình luận/đánh giá sản phẩm
    Route::prefix('product-comments')->name('products.comments.')->group(function () {
        Route::get('/products-with-comments', [ProductCommentAdminController::class, 'productsWithComments'])->name('products-with-comments');
        Route::get('/', [ProductCommentAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [ProductCommentAdminController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductCommentAdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductCommentAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductCommentAdminController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/approve', [ProductCommentAdminController::class, 'approve'])->name('approve');
        Route::patch('/{id}/toggle', [ProductCommentAdminController::class, 'toggleStatus'])->name('toggle');
    });
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Client
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/account/show', [AccountController::class, 'show'])->name('account.show');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/product-comments/{id}/reply', [ProductCommentAdminController::class, 'reply'])->name('products.comments.reply');
