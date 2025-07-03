<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'id'    => 1,
            'name'           => 'Laptop Dell XPS 13',
            'brand_id'       => 1,
            'price'          => 25000000,
            'discount_price' => 23000000,
            'stock'          => 10,
            'description'    => 'Laptop cao cấp, mỏng nhẹ, pin lâu.',
            'status'         => 'active',
        ]);

        Product::create([
            'id'    => 2,
            'name'           => 'iPhone 15 Pro',
            'brand_id'       => 2,
            'price'          => 32000000,
            'discount_price' => 30000000,
            'stock'          => 15,
            'description'    => 'Điện thoại cao cấp của Apple.',
            'status'         => 'active',
        ]);
    }
}
