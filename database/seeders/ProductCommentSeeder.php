<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductComment;

class ProductCommentSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ProductComment::create([
            'user_id' => 1,
            'product_id' => 1,
            'content' => 'This product is awesome!',
            'is_hidden' => false,
            'created_at' => now(),
        ]);

        ProductComment::create([
            'user_id' => 2,
            'product_id' => 2,
            'content' => 'Not bad, but could be improved.',
            'is_hidden' => false,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ProductComment::factory()->count(5)->create();
    }
}
