<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Tag::create([
            'name' => 'Electronics',
        ]);

        Tag::create([
            'name' => 'Fashion',
        ]);

        Tag::create([
            'name' => 'Home & Kitchen',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Tag::factory()->count(5)->create();
    }
}
