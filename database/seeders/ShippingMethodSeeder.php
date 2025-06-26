<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;

class ShippingMethodSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ShippingMethod::create([
            'name' => 'Standard Shipping',
            'description' => '5-7 days delivery.',
            'fee' => 10.00,
            'estimated_days' => 7,
            'max_weight' => 10.00,
            'regions' => 'Hà Nội, TP.HCM, Bình Dương',
        ]);

        ShippingMethod::create([
            'name' => 'Express Shipping',
            'description' => '1-2 days delivery.',
            'fee' => 20.00,
            'estimated_days' => 2,
            'max_weight' => 5.00,
            'regions' => 'Hà Nội, TP.HCM',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ShippingMethod::factory()->count(5)->create();
    }
}
