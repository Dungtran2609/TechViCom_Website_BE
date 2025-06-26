<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;

class WishlistSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Wishlist::create([
            'user_id' => 1,
            'product_id' => 1,
            'product_variant_id' => 1,
            'created_at' => now(),
        ]);

        Wishlist::create([
            'user_id' => 2,
            'product_id' => 2,
            'product_variant_id' => 2,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Wishlist::factory()->count(5)->create();
    }
}
