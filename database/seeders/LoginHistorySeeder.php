<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoginHistory;

class LoginHistorySeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        LoginHistory::create([
            'user_id' => 1,
            'ip_address' => '192.168.1.1',
            'device_info' => 'Chrome on Windows',
            'login_status' => 'success',
            'created_at' => now(),
        ]);

        LoginHistory::create([
            'user_id' => 2,
            'ip_address' => '192.168.1.2',
            'device_info' => 'Safari on macOS',
            'login_status' => 'failed',
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        LoginHistory::factory()->count(5)->create();
    }
}
