<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Permission::create([
            'name' => 'view_dashboard',
            'description' => 'View the admin dashboard',
        ]);

        Permission::create([
            'name' => 'edit_product',
            'description' => 'Edit product details',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Permission::factory()->count(5)->create();
    }
}
