<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Review::create([
            'user_id' => 1,
            'product_id' => 1,
            'rating' => 5,
            'comment_text' => 'Excellent product!',
            'is_approved' => true,
            'created_at' => now(),
        ]);

        Review::create([
            'user_id' => 2,
            'product_id' => 2,
            'rating' => 4,
            'comment_text' => 'Very good, but could be improved.',
            'is_approved' => true,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Review::factory()->count(5)->create();
    }
}
