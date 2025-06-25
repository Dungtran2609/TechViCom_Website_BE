<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Banner::create([
            'image_url' => 'images/banner1.jpg',
            'link_url' => 'https://example.com/promotion',
            'position' => 'top',
            'is_active' => true,
        ]);

        Banner::create([
            'image_url' => 'images/banner2.jpg',
            'link_url' => 'https://example.com/sale',
            'position' => 'bottom',
            'is_active' => true,
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Banner::factory()->count(5)->create();
    }
}
