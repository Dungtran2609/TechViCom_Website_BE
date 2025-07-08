<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy tất cả product_variants
        $variants = DB::table('product_variants')->pluck('id');

        // Lấy tất cả attribute_value
        $attributeValues = DB::table('attribute_values')->pluck('id');

        foreach ($variants as $variantId) {
            // Random 1-3 attribute_value cho mỗi variant
            foreach ($faker->randomElements($attributeValues->toArray(), rand(1, 3)) as $attrValId) {
                DB::table('product_variants_attributes')->insert([
                    'product_variant_id' => $variantId,
                    'attribute_value_id' => $attrValId,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }
        }
    }
}
