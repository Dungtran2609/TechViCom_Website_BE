<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionRole;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        PermissionRole::create([
            'role_id' => 1,
            'permission_id' => 1,
        ]);

        PermissionRole::create([
            'role_id' => 2,
            'permission_id' => 2,
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        PermissionRole::factory()->count(5)->create();
    }
}
