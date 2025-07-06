<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariantAttribute;

class ProductVariantAttributeSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ProductVariantAttribute::create([
            'product_variant_id' => 1,
            'attribute_value_id' => 1,
            'created_at' => now(),
        ]);

        ProductVariantAttribute::create([
            'product_variant_id' => 2,
            'attribute_value_id' => 2,
            'created_at' => now(),
        ]);

    }
}
