<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAddress;

class UserAddressSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        UserAddress::create([
            'user_id' => 1,
            'address_line' => '123 Main St',
            'ward' => 'Ward 1',
            'district' => 'District 1',
            'city' => 'Hà Nội',
            'is_default' => true,
            'created_at' => now(),
        ]);

        UserAddress::create([
            'user_id' => 2,
            'address_line' => '456 Another St',
            'ward' => 'Ward 2',
            'district' => 'District 2',
            'city' => 'TP.HCM',
            'is_default' => false,
            'created_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        UserAddress::factory()->count(5)->create();
    }
}
