<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Product::create([
            'category_id' => 1,
            'brand_id' => 1,
            'name' => 'Product 1',
            'sku' => 'PROD-001',
            'price' => 100.00,
            'discount_price' => 80.00,
            'weight' => 1.5,
            'dimensions' => '30x20x10 cm',
            'stock' => 50,
            'description' => 'This is a test product.',
            'status' => 'available',
            'created_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Product::create([
            'category_id' => 2,
            'brand_id' => 2,
            'name' => 'Product 2',
            'sku' => 'PROD-002',
            'price' => 200.00,
            'discount_price' => 150.00,
            'weight' => 2.0,
            'dimensions' => '40x30x20 cm',
            'stock' => 30,
            'description' => 'This is another test product.',
            'status' => 'available',
            'created_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Product::factory()->count(5)->create();
    }
}
