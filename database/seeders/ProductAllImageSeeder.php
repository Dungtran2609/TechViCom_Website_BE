<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAllImage;

class ProductAllImageSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ProductAllImage::create([
            'product_id' => 1,
            'variant_id' => 1,
            'img_url' => 'images/product1.jpg',
            'is_primary' => true,
            'created_at' => now(),
        ]);

        ProductAllImage::create([
            'product_id' => 2,
            'variant_id' => 2,
            'img_url' => 'images/product2.jpg',
            'is_primary' => true,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ProductAllImage::factory()->count(5)->create();
    }
}
