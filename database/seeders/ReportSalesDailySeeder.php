<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportSalesDaily;

class ReportSalesDailySeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ReportSalesDaily::create([
            'report_date' => '2025-06-25',
            'total_orders' => 100,
            'total_revenue' => 5000.00,
            'total_refunds' => 100.00,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ReportSalesDaily::factory()->count(5)->create();
    }
}
