<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Warehouse::create([
            'name' => 'Warehouse 1',
            'address' => '123 Warehouse St, Hà Nội',
            'created_at' => now(),
        ]);

        Warehouse::create([
            'name' => 'Warehouse 2',
            'address' => '456 Warehouse St, TP.HCM',
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Warehouse::factory()->count(5)->create();
    }
}
