<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefundRequest;

class RefundRequestSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        RefundRequest::create([
            'order_id' => 1,
            'user_id' => 1,
            'reason' => 'Product defective.',
            'status' => 'pending',
            'created_at' => now(),
        ]);

        RefundRequest::create([
            'order_id' => 2,
            'user_id' => 2,
            'reason' => 'Wrong product received.',
            'status' => 'approved',
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        RefundRequest::factory()->count(5)->create();
    }
}
