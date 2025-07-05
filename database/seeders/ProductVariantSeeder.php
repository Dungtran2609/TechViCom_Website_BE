<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ProductVariant::create([
            'product_id' => 1,
            'sku' => 'PROD-001-V1',
            'price' => 100.00,
            'sale_price' => 80.00,
            'weight' => 1.5,
            'dimensions' => '30x20x10 cm',
            'stock' => 50,
            'image' => 'https://picsum.photos/200',
            'created_at' => now(),
        ]);

        ProductVariant::create([
            'product_id' => 2,
            'sku' => 'PROD-002-V1',
            'price' => 200.00,
            'sale_price' => 150.00,
            'weight' => 2.0,
            'dimensions' => '40x30x20 cm',
            'stock' => 30,
            'image' => 'https://picsum.photos/200',
            'created_at' => now(),
        ]);

        
    }
}
