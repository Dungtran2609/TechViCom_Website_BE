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
                'name' => 'ASUS ROG Zephyrus G14',
                'type' => 'simple',
                'sku' => 'AS-ROG-ZPG14',
                'price' => 42990000,
                'sale_price' => 39990000,
                'stock' => 25,
                'brand_id' => 3,
                'category_id' => 3,
                'is_featured' => true,
            ],
            [
                'name' => 'Xiaomi 13T Pro 5G',
                'type' => 'simple',
                'sku' => 'XI-13T-PRO-5G',
                'price' => 16990000,
                'sale_price' => 15490000,
                'stock' => 120,
                'brand_id' => 4,
                'category_id' => 2,
                'is_featured' => false,
            ],
            [
                'name' => 'iPhone 15 Pro',
                'type' => 'variable',
                'sku' => 'IP15-PRO-BASE',
                'price' => 28990000,
                'sale_price' => null,
                'stock' => 130,
                'brand_id' => 1,
                'category_id' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Samsung Galaxy S23 FE',
                'type' => 'variable',
                'sku' => 'SS-S23-FE-BASE',
                'price' => 14890000,
                'sale_price' => 13500000,
                'stock' => 110,
                'brand_id' => 2,
                'category_id' => 2,
                'is_featured' => true,
            ]
        ];

        foreach ($products as $productData) {
            Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'sku' => $productData['sku'],
                'type' => $productData['type'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'stock' => $productData['stock'],
                'low_stock_amount' => 10,
                'short_description' => 'Sản phẩm ' . $productData['name'] . ' chính hãng, chất lượng cao.',
                'long_description' => '<p>Mô tả chi tiết và thông số kỹ thuật cho ' . $productData['name'] . '. Bảo hành 12 tháng.</p>',
                'thumbnail' => 'products/default.jpg',
                'status' => 'active',
                'is_featured' => $productData['is_featured'],
                'view_count' => rand(300, 7000),
                'brand_id' => $productData['brand_id'],
                'category_id' => $productData['category_id'],
            ]);
        }
    }
}