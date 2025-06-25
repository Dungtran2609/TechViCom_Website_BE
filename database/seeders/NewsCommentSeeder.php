<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsComment;

class NewsCommentSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        NewsComment::create([
            'user_id' => 1,
            'news_id' => 1,
            'content' => 'Great article!',
            'is_hidden' => false,
            'created_at' => now(),
        ]);

        NewsComment::create([
            'user_id' => 2,
            'news_id' => 2,
            'content' => 'Very informative, thanks for sharing.',
            'is_hidden' => false,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        NewsComment::factory()->count(5)->create();
    }
}
