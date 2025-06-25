<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserNotification;

class UserNotificationSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        UserNotification::create([
            'user_id' => 1,
            'notification_id' => 1,
            'is_read' => false,
            'created_at' => now(),
        ]);

        UserNotification::create([
            'user_id' => 2,
            'notification_id' => 2,
            'is_read' => true,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        UserNotification::factory()->count(5)->create();
    }
}
