<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        News::create([
            'title' => 'Breaking News: New product launch',
            'content' => 'The latest product has been launched and is now available.',
            'author_id' => 1,
            'status' => 'published',
            'published_at' => now(),
        ]);

        News::create([
            'title' => 'Tech News: New mobile updates',
            'content' => 'Mobile updates have been rolled out with new features.',
            'author_id' => 2,
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        News::factory()->count(5)->create();
    }
}
