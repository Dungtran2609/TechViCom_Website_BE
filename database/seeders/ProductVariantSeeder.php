<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy danh sách products đã seed
        $products = DB::table('products')->pluck('id');

        foreach ($products as $productId) {
            for ($i = 0; $i < 5; $i++) {
                DB::table('product_variants')->insert([
                    'product_id' => $productId,
                    'sku'        => strtoupper($faker->bothify('SKU-####??')),
                    'price'      => $faker->numberBetween(100000, 500000),
                    'sale_price' => $faker->optional()->numberBetween(80000, 400000),
                    'weight'     => $faker->randomFloat(2, 0.1, 5),
                    'dimensions' => $faker->numberBetween(10, 100) . 'x' . $faker->numberBetween(10, 100) . 'x' . $faker->numberBetween(10, 100),
                    'stock'      => $faker->numberBetween(0, 50),
                    'image'      => $faker->optional()->imageUrl(400, 400, 'products'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
