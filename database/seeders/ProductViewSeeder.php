<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductView;

class ProductViewSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ProductView::create([
            'user_id' => 1,
            'product_id' => 1,
            'viewed_at' => now(),
        ]);

        ProductView::create([
            'user_id' => 2,
            'product_id' => 2,
            'viewed_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ProductView::factory()->count(5)->create();
    }
}
