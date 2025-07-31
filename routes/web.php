<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Admin\News\NewsCategoryController;
use App\Http\Controllers\Admin\Products\BrandController;
use App\Http\Controllers\Admin\Products\CategoryController;
use App\Http\Controllers\Admin\Products\AttributeController;
use App\Http\Controllers\Admin\Products\AttributeValueController;
use App\Http\Controllers\Admin\Products\ProductController;
use App\Http\Controllers\Admin\Products\ProductVariantController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Users\RoleController;
use App\Http\Controllers\Admin\Users\PermissionController;
use App\Http\Controllers\Admin\Contacts\ContactsAdminController;
use App\Http\Controllers\Admin\Coupons\CouponController;
use App\Http\Controllers\Admin\News\NewsCommentController;
use App\Http\Controllers\Admin\OrderController;

// --- Admin routes ---
Route::middleware(['auth', IsAdmin::class])->prefix('admin-control')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::resource('products/categories', CategoryController::class)->names('products.categories');
    Route::get('products/categories/trashed', [CategoryController::class, 'trashed'])->name('products.categories.trashed');
    Route::post('products/categories/{id}/restore', [CategoryController::class, 'restore'])->name('products.categories.restore');
    Route::delete('products/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('products.categories.force-delete');

    Route::resource('products/brands', BrandController::class)->names('products.brands');
    Route::get('products/brands/trashed', [BrandController::class, 'trashed'])->name('products.brands.trashed');
    Route::post('products/brands/{id}/restore', [BrandController::class, 'restore'])->name('products.brands.restore');
    Route::delete('products/brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('products.brands.force-delete');

    Route::resource('products/attributes', AttributeController::class)->names('products.attributes');
    Route::get('products/attributes/trashed', [AttributeController::class, 'trashed'])->name('products.attributes.trashed');
    Route::post('products/attributes/{id}/restore', [AttributeController::class, 'restore'])->name('products.attributes.restore');
    Route::delete('products/attributes/{id}/force-delete', [AttributeController::class, 'forceDelete'])->name('products.attributes.force-delete');

    Route::prefix('products/attributes')->name('products.attributes.')->group(function () {
        Route::get('{attribute}/values', [AttributeValueController::class, 'index'])->name('values.index');
        Route::get('{attribute}/values/trashed', [AttributeValueController::class, 'trashed'])->name('values.trashed');
        Route::post('values/{id}/restore', [AttributeValueController::class, 'restore'])->name('values.restore');
        Route::delete('values/{id}/force-delete', [AttributeValueController::class, 'forceDelete'])->name('values.force-delete');
        Route::post('{attribute}/values', [AttributeValueController::class, 'store'])->name('values.store');
        Route::get('values/{value}/edit', [AttributeValueController::class, 'edit'])->name('values.edit');
        Route::put('values/{value}', [AttributeValueController::class, 'update'])->name('values.update');
        Route::delete('values/{value}', [AttributeValueController::class, 'destroy'])->name('values.destroy');
    });

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

    Route::resource('products', ProductController::class)->names('products');
    Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    // News
    Route::resource('news', NewsController::class);
    Route::resource('news-categories', NewsCategoryController::class);

    // Contacts
    Route::get('contacts', [ContactsAdminController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{id}', [ContactsAdminController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{id}', [ContactsAdminController::class, 'destroy'])->name('contacts.destroy');

    // Users
    Route::prefix('users')->middleware(CheckRole::class . ':admin')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/admins', [UserController::class, 'admins'])->name('admins');
        Route::get('/staffs', [UserController::class, 'staffs'])->name('staffs');
        Route::get('/customers', [UserController::class, 'customers'])->name('customers');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('forceDelete');
    });

    // Roles
    Route::prefix('roles')->middleware(CheckRole::class . ':admin')->name('roles.')->group(function () {
        Route::resource('/', RoleController::class)->parameters(['' => 'role']);
        Route::get('trashed', [RoleController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [RoleController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [RoleController::class, 'forceDelete'])->name('force-delete');
        Route::get('list', [RoleController::class, 'list'])->name('list');
        Route::post('update-users', [RoleController::class, 'updateUsers'])->name('updateUsers');
    });

    // Permissions
    Route::prefix('permissions')->middleware(CheckRole::class . ':admin')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/list', [PermissionController::class, 'list'])->name('list');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/trashed', [PermissionController::class, 'trashed'])->name('trashed');
        Route::post('/{id}/restore', [PermissionController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [PermissionController::class, 'forceDelete'])->name('forceDelete');

        // 👇 Thêm dòng này để fix lỗi:
        Route::post('/update-roles', [PermissionController::class, 'updateRoles'])->name('updateRoles');
    });


    // Coupons
    Route::prefix('coupons')->middleware(CheckPermission::class . ':manage_coupons')->name('coupons.')->group(function () {
        Route::resource('/', CouponController::class)->parameters(['' => 'coupon'])->except(['show']);
        Route::put('{id}/restore', [CouponController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [CouponController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::prefix('order')->name('order.')->group(function () {
        Route::get('trashed', [OrderController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [OrderController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [OrderController::class, 'forceDelete'])->name('force-delete');
        Route::post('{id}/update-status', [OrderController::class, 'updateOrders'])->name('updateOrders');
        Route::get('returns', [OrderController::class, 'returnsIndex'])->name('returns');
        Route::post('returns/{id}/process', [OrderController::class, 'processReturn'])->name('process-return');
        Route::resource('', OrderController::class)->parameters(['' => 'order'])->names('');
    });

    // News
    Route::resource('news', NewsController::class);
    Route::resource('news-categories', NewsCategoryController::class);
    Route::get('/news-comments', [NewsCommentController::class, 'index'])->name('news-comments.index');
    Route::delete('/news-comments/{id}', [NewsCommentController::class, 'destroy'])->name('news-comments.destroy');
    Route::patch('/news-comments/{id}/toggle', [NewsCommentController::class, 'toggleVisibility'])->name('news-comments.toggle');

    Route::resource('banner', BannerController::class);
});

// --- Auth routes ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Public route ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Authenticated user routes ---
Route::middleware('auth')->group(function () {
    Route::get('/account/show', [AccountController::class, 'show'])->name('account.show');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
