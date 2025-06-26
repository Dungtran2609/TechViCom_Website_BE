<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Category::create([
            'parent_id' => null,
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);

        Category::create([
            'parent_id' => null,
            'name' => 'Clothing',
            'slug' => 'clothing',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Category::factory()->count(5)->create();
    }
}
