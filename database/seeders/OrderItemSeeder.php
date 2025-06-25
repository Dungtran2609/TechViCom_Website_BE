<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        OrderItem::create([
            'order_id' => 1,
            'variant_id' => 1,
            'quantity' => 2,
            'product_id' => 1,
            'image_product' => 'product_image.jpg',
            'name_product' => 'Product 1',
            'price' => 100.00,
        ]);

        OrderItem::create([
            'order_id' => 1,
            'variant_id' => 2,
            'quantity' => 1,
            'product_id' => 2,
            'image_product' => 'product_image.jpg',
            'name_product' => 'Product 2',
            'price' => 150.00,
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        OrderItem::factory()->count(5)->create();
    }
}
