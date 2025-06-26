<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportUserActivity;

class ReportUserActivitySeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ReportUserActivity::create([
            'user_id' => 1,
            'activity_date' => '2025-06-25',
            'login_count' => 10,
            'order_count' => 5,
            'comment_count' => 3,
            'page_views' => 50,
            'time_spent' => 1200,  // 20 minutes in seconds
            'interactions' => 15,
            'created_at' => now(),
        ]);

        ReportUserActivity::create([
            'user_id' => 2,
            'activity_date' => '2025-06-25',
            'login_count' => 8,
            'order_count' => 4,
            'comment_count' => 2,
            'page_views' => 40,
            'time_spent' => 1000,  // 16 minutes in seconds
            'interactions' => 12,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ReportUserActivity::factory()->count(5)->create();
    }
}
