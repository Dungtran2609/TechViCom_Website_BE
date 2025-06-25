<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariantsAttribute;

class ProductVariantsAttributeSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ProductVariantsAttribute::create([
            'product_variant_id' => 1,
            'attribute_value_id' => 1,
            'created_at' => now(),
        ]);

        ProductVariantsAttribute::create([
            'product_variant_id' => 2,
            'attribute_value_id' => 2,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ProductVariantsAttribute::factory()->count(5)->create();
    }
}
