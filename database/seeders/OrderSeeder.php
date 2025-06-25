<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Order::create([
            'user_id' => 1,
            'address_id' => 1,
            'payment_method' => 'Credit Card',
            'status' => 'pending',
            'total_amount' => 200.00,
            'recipient_name' => 'John Doe',
            'recipient_phone' => '1234567890',
            'recipient_address' => '123 Main St, City, Country',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Order::factory()->count(5)->create();
    }
}
