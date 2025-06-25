<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Brand::create([
            'name' => 'Nike',
            'description' => 'Leading global sportswear brand.',
            'created_at' => now(),
        ]);

        Brand::create([
            'name' => 'Adidas',
            'description' => 'Global leader in sportswear and accessories.',
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Brand::factory()->count(5)->create();
    }
}
