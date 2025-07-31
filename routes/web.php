<?php

use App\Http\Middleware\IsAdmin;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Admin\Contacts\ContactsAdminController;
use App\Http\Controllers\Admin\News\NewsCategoryController;
use App\Http\Controllers\Admin\News\NewsCommentController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Admin\Products\BrandController;
use App\Http\Controllers\Admin\Products\ProductController;
use App\Http\Controllers\Admin\Products\ProductVariantController;
use App\Http\Controllers\Admin\Products\CategoryController;
use App\Http\Controllers\Admin\Products\AttributeController;
use App\Http\Controllers\Admin\Products\AttributeValueController;
use App\Http\Controllers\Admin\Users\PermissionController;
use App\Http\Controllers\Admin\Users\RoleController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckPermission;


Route::middleware([IsAdmin::class])->prefix('admin-control')->name('admin.')->group(function () {
    // Trang dashboard admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

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
    // Chọn sản phẩm để quản lý biến thể
    Route::get('products/variants/select', [ProductVariantController::class, 'selectProduct'])->name('products.variants.select');


    // Biến thể sản phẩm
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('variants/{variant}', [ProductVariantController::class, 'show'])->name('variants.show');
        Route::get('{product}/variants', [ProductVariantController::class, 'index'])->name('variants.index');
        Route::get('{product}/variants/create', [ProductVariantController::class, 'create'])->name('variants.create');
        Route::post('{product}/variants', [ProductVariantController::class, 'store'])->name('variants.store');
        Route::get('variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
        Route::put('variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
        Route::delete('variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
    });


    // Quản lý bài viết
    Route::resource('news', NewsController::class);
    Route::resource('news-categories', NewsCategoryController::class);
    Route::get('/news-comments', [NewsCommentController::class, 'index'])->name('news-comments.index');
    Route::delete('/news-comments/{id}', [NewsCommentController::class, 'destroy'])->name('news-comments.destroy');
    Route::patch('/news-comments/{id}/toggle', [NewsCommentController::class, 'toggleVisibility'])->name('news-comments.toggle');

    // Quản lý liên hệ
    Route::get('contacts', [ContactsAdminController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{id}', [ContactsAdminController::class, 'show'])->name('contacts.show');

    Route::delete('contacts/{id}', [ContactsAdminController::class, 'destroy'])->name('contacts.destroy');

    // Quản lý sản phẩm
    Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::resource('products', ProductController::class)->names('products');

    // =================== USERS MANAGEMENT (admin, manager) ===================
    Route::prefix('users')->name('users.')->group(function () {
        // Danh sách người dùng chung + tìm kiếm
        Route::get('/', [UserController::class, 'index'])->name('index');

        // Danh sách chia theo vai trò
        Route::get('/admins', [UserController::class, 'admins'])->name('admins');
        Route::get('/staffs', [UserController::class, 'staffs'])->name('staffs');
        Route::get('/customers', [UserController::class, 'customers'])->name('customers');

        // Thêm, sửa, xóa người dùng
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // Thùng rác, khôi phục, xóa vĩnh viễn
        Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('forceDelete');

        // Quản lý địa chỉ người dùng
        Route::get('/{user}/addresses', [UserController::class, 'addresses'])->name('addresses.index');
        Route::post('/{user}/addresses', [UserController::class, 'addAddress'])->name('addresses.store');
        Route::put('/addresses/{address}', [UserController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{address}', [UserController::class, 'deleteAddress'])->name('addresses.destroy');
    });

    // =================== ROLES MANAGEMENT (chỉ admin) ===================
    Route::prefix('roles')->name('roles.')->group(function () {
        // Thùng rác, khôi phục, xóa vĩnh viễn
        Route::get('trashed', [RoleController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [RoleController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [RoleController::class, 'forceDelete'])->name('force-delete');

        // Danh sách roles riêng (ví dụ dùng để phân trang)
        Route::get('list', [RoleController::class, 'list'])->name('list');

        // ✅ Gán vai trò cho người dùng
        Route::post('update-users', [RoleController::class, 'updateUsers'])->name('updateUsers');

        // Gán quyền cho vai trò
        Route::middleware(CheckPermission::class . ':assign_permission')
            ->get('{role}/permissions', [PermissionController::class, 'permissions'])->name('permissions.edit');
        Route::middleware(CheckPermission::class . ':assign_permission')
            ->put('{role}/permissions', [PermissionController::class, 'updatePermissions'])->name('permissions.update');

        // CRUD tài nguyên chính (nên để sau cùng để tránh override)
        Route::resource('/', RoleController::class)->parameters(['' => 'role']);
    });



    Route::prefix('permissions')->name('permissions.')->group(function () {
        // Xem danh sách quyền (dạng ma trận + danh sách phân trang)
        Route::middleware(CheckPermission::class . ':view_permission')
            ->get('/', [PermissionController::class, 'index'])->name('index');
        Route::middleware(CheckPermission::class . ':view_permission')
            ->get('/list', [PermissionController::class, 'list'])->name('list');

        // Thêm quyền
        Route::middleware(CheckPermission::class . ':create_permission')
            ->get('/create', [PermissionController::class, 'create'])->name('create');
        Route::middleware(CheckPermission::class . ':create_permission')
            ->post('/', [PermissionController::class, 'store'])->name('store');

        // Sửa quyền
        Route::middleware(CheckPermission::class . ':edit_permission')
            ->get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::middleware(CheckPermission::class . ':edit_permission')
            ->put('/{permission}', [PermissionController::class, 'update'])->name('update');

        // Xoá mềm quyền
        Route::middleware(CheckPermission::class . ':delete_permission')
            ->delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');

        // Gán quyền cho vai trò (từ bảng ma trận)
        Route::middleware(CheckPermission::class . ':assign_permission')
            ->post('/update-roles', [PermissionController::class, 'updateRoles'])->name('updateRoles');

        // 📦 Thùng rác - quyền đã xoá mềm
        Route::middleware(CheckPermission::class . ':delete_permission')
            ->get('/trashed', [PermissionController::class, 'trashed'])->name('trashed');

        Route::middleware(CheckPermission::class . ':delete_permission')
            ->post('/{id}/restore', [PermissionController::class, 'restore'])->name('restore');

        Route::middleware(CheckPermission::class . ':delete_permission')
            ->delete('/{id}/force-delete', [PermissionController::class, 'forceDelete'])->name('forceDelete');
    });
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/account/show', [AccountController::class, 'show'])->name('account.show');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// coupons
use App\Http\Controllers\Admin\Coupons\CouponController;

Route::prefix('admin-control')->name('admin.')->middleware(['auth'])->group(function () {
    Route::middleware(['permission:manage_coupons'])->group(function () {
        Route::resource('coupons', CouponController::class)->except(['show']);
        Route::put('coupons/{id}/restore', [CouponController::class, 'restore'])->name('coupons.restore');
        Route::delete('coupons/{id}/force-delete', [CouponController::class, 'forceDelete'])->name('coupons.forceDelete');
    });
});

