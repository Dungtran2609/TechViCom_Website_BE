<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'description' => 'Chuyên các sản phẩm iPhone, MacBook, iPad.',
                'image' => 'brands/apple.png',
                'status' => 1,
            ],
            [
                'name' => 'Samsung',
                'description' => 'Thương hiệu điện thoại Android và thiết bị gia dụng.',
                'image' => 'brands/samsung.png',
                'status' => 1,
            ],
            [
                'name' => 'ASUS',
                'description' => 'Chuyên laptop văn phòng, gaming, bo mạch chủ.',
                'image' => 'brands/asus.png',
                'status' => 1,
            ],
            [
                'name' => 'Xiaomi',
                'description' => 'Điện thoại thông minh và thiết bị IoT giá rẻ.',
                'image' => 'brands/xiaomi.png',
                'status' => 1,
            ],
        ];

        foreach ($brands as $data) {
            Brand::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'image' => $data['image'], // đảm bảo file ảnh tồn tại ở storage/public/brands/
                'status' => $data['status'],
            ]);
        }
    }
}
