<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\AttributeController;
use App\Http\Controllers\Admin\Product\AttributeValueController;


// Chuyển hướng trang chủ vào dashboard
Route::get('/', function () {
    return view('admin.dashboard');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories routes
    Route::get('products/categories/trashed', [CategoryController::class, 'trashed'])->name('products.categories.trashed');
    Route::post('products/categories/{id}/restore', [CategoryController::class, 'restore'])->name('products.categories.restore');
    Route::delete('products/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('products.categories.force-delete');
    Route::resource('products/categories', CategoryController::class)->names('products.categories');;

    // Attributes routes
    Route::get('products/attributes/trashed', [AttributeController::class, 'trashed'])->name('products.attributes.trashed');
    Route::post('products/attributes/{id}/restore', [AttributeController::class, 'restore'])->name('products.attributes.restore');
    Route::delete('products/attributes/{id}/force-delete', [AttributeController::class, 'forceDelete'])->name('products.attributes.force-delete');
    Route::resource('products/attributes', AttributeController::class)->names('products.attributes');

    // Atribute values routes
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
});
