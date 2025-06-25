<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportTopProduct;

class ReportTopProductSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        ReportTopProduct::create([
            'product_id' => 1,
            'sold_quantity' => 150,
            'report_date' => '2025-06-25',
        ]);

        ReportTopProduct::create([
            'product_id' => 2,
            'sold_quantity' => 120,
            'report_date' => '2025-06-25',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        ReportTopProduct::factory()->count(5)->create();
    }
}
