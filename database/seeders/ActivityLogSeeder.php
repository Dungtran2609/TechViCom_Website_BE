<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ActivityLog::create([
            'user_id' => 1,
            'action_type' => 'Login',
            'description' => 'User logged in successfully.',
            'target_table' => 'users',
            'target_id' => 1,
            'created_at' => now(),
        ]);

        // Thêm các bản ghi thực tế khác nếu cần
        ActivityLog::create([
            'user_id' => 2,
            'action_type' => 'Purchase',
            'description' => 'User made a purchase.',
            'target_table' => 'orders',
            'target_id' => 10,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 10 bản ghi ngẫu nhiên
        ActivityLog::factory()->count(10)->create();
    }
}
