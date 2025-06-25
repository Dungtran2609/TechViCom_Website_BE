<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Coupon::create([
            'code' => 'SUMMER2025',
            'discount_type' => 'percentage',
            'value' => 10,
            'max_discount_amount' => 50,
            'min_order_value' => 100,
            'max_order_value' => 500,
            'max_usage_per_user' => 1,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => true,
        ]);

        Coupon::create([
            'code' => 'WINTER2025',
            'discount_type' => 'fixed',
            'value' => 20,
            'max_discount_amount' => 50,
            'min_order_value' => 100,
            'max_order_value' => 500,
            'max_usage_per_user' => 3,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => true,
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Coupon::factory()->count(5)->create();
    }
}
