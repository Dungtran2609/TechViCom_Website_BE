<?php
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;

Route::post('/shipping-fee', [ShippingController::class, 'calculateShipping']);
