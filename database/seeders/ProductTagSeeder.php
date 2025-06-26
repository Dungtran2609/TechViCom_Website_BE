<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductTag;

class ProductTagSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ProductTag::create([
            'product_id' => 1,
            'tag_id' => 1,
        ]);

        ProductTag::create([
            'product_id' => 2,
            'tag_id' => 2,
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ProductTag::factory()->count(5)->create();
    }
}
