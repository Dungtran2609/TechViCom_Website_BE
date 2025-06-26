<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        UserRole::create([
            'user_id' => 1,
            'role_id' => 1, // Role Admin
        ]);

        UserRole::create([
            'user_id' => 2,
            'role_id' => 2, // Role User
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        UserRole::factory()->count(5)->create();
    }
}
