<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro Max 256GB',
                'brand_id' => 1, // Apple
                'category_id' => 2, // Điện thoại Apple
                'sku' => 'IP15PM-256',
                'price' => 34990000,
                'sale_price' => 32990000,
                'stock' => 50,
                'low_stock_amount' => 5,
                'thumbnail' => 'products/iphone-15-pro-max.jpg',
                'short_description' => 'iPhone 15 Pro Max - smartphone cao cấp nhất của Apple.',
                'long_description' => '<p>iPhone 15 Pro Max sở hữu thiết kế titan siêu bền, chip A17 Pro mạnh mẽ và cụm camera tele zoom quang học 5x.</p><ul><li>Màn hình Super Retina XDR 6.7 inch</li><li>Chip A17 Pro mới nhất</li><li>Pin dùng 1.5 ngày</li></ul>',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB',
                'brand_id' => 2, // Samsung
                'category_id' => 1, // Điện thoại
                'sku' => 'SGS24U-512',
                'price' => 33990000,
                'sale_price' => 31990000,
                'stock' => 40,
                'low_stock_amount' => 4,
                'thumbnail' => 'products/galaxy-s24-ultra.jpg',
                'short_description' => 'Samsung Galaxy S24 Ultra - hiệu năng mạnh, bút S-Pen tiện lợi.',
                'long_description' => '<p>Galaxy S24 Ultra nổi bật với vi xử lý Snapdragon 8 Gen 3, camera 200MP siêu nét và màn hình AMOLED 120Hz sắc nét.</p><ul><li>Bút S-Pen tích hợp</li><li>Pin 5000mAh</li><li>Camera zoom 100x</li></ul>',
            ],
            [
                'name' => 'ASUS ROG Strix G16 (RTX 4060)',
                'brand_id' => 3, // ASUS
                'category_id' => 3, // Laptop Gaming
                'sku' => 'ROG-G16-4060',
                'price' => 39990000,
                'sale_price' => 37990000,
                'stock' => 20,
                'low_stock_amount' => 3,
                'thumbnail' => 'products/asus-rog-strix-g16.jpg',
                'short_description' => 'Laptop gaming ASUS ROG Strix G16 với hiệu năng đỉnh cao.',
                'long_description' => '<p>ROG Strix G16 trang bị CPU Intel Core i7 Gen 13 và GPU RTX 4060 mạnh mẽ, thích hợp chơi game và đồ họa nặng.</p><ul><li>Màn hình 16” FHD 165Hz</li><li>RAM 16GB, SSD 512GB</li><li>Hệ thống tản nhiệt thông minh</li></ul>',
            ],
        ];

        foreach ($products as $index => $data) {
            Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . ($index + 1),
                'sku' => $data['sku'],
                'type' => 'simple',
                'price' => $data['price'],
                'sale_price' => $data['sale_price'],
                'stock' => $data['stock'],
                'low_stock_amount' => $data['low_stock_amount'],
                'short_description' => $data['short_description'],
                'long_description' => $data['long_description'],
                'thumbnail' => $data['thumbnail'],
                'status' => 'active',
                'brand_id' => $data['brand_id'],
                'category_id' => $data['category_id'],
            ]);
        }
    }
}
