<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    
    public function run(): void
{
    Coupon::insert([
        [
            'code' => 'SALE50',
            'discount_type' => 'percent',
            'value' => 50,
            'max_discount_amount' => 100000,
            'min_order_value' => 200000,
            'max_order_value' => 1000000,
            'max_usage_per_user' => 2,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(30),
            'status' => 1,
        ],
        [
            'code' => 'SALE100',
            'discount_type' => 'percent',
            'value' => 50,
            'max_discount_amount' => 100000,
            'min_order_value' => 200000,
            'max_order_value' => 1000000,
            'max_usage_per_user' => 2,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(30),
            'status' => 1,
        ],
    ]);
}

}