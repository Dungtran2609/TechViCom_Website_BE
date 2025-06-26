<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Notification::create([
            'title' => 'Welcome to the platform!',
            'message' => 'Thank you for joining us. We hope you enjoy our services.',
            'type' => 'personal',
            'created_at' => now(),
        ]);

        Notification::create([
            'title' => 'New updates available',
            'message' => 'Check out the latest updates on your dashboard.',
            'type' => 'global',
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Notification::factory()->count(5)->create();
    }
}
