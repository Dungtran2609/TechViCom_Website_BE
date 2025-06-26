<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Role::create([
            'name' => 'Admin',
        ]);
        
        Role::create([
            'name' => 'User',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Role::factory()->count(5)->create();
    }
}
