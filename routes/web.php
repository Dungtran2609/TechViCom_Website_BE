<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\AccountController;

Route::get('/admin-control', [AdminController::class, 'dashboard'])
    ->middleware([IsAdmin::class])
    ->name('admin.dashboard');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('admin/products')->name('admin.products.')->group(function () {
    Route::get('categories', [ProductCategoryController::class, 'index'])->name('categories.index');
    Route::get('attributes', [ProductAttributeController::class, 'index'])->name('attributes.index');
    // ... các route khác ...
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    // ... các route admin khác ...
});

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

use App\Http\Controllers\Client\ProductController;

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

