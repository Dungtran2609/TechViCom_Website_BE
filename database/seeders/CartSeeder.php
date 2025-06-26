<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Cart::create([
            'user_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'product_variant_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Cart::factory()->count(5)->create();
    }
}
