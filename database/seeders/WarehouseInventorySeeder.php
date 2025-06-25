<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WarehouseInventory;

class WarehouseInventorySeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        WarehouseInventory::create([
            'warehouse_id' => 1,
            'product_variant_id' => 1,
            'stock' => 50,
        ]);

        WarehouseInventory::create([
            'warehouse_id' => 2,
            'product_variant_id' => 2,
            'stock' => 30,
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        WarehouseInventory::factory()->count(5)->create();
    }
}
