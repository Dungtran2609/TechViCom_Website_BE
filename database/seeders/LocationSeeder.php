<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run()
    {
        // Provinces (Tỉnh/Thành phố)
        DB::table('provinces')->insert([
            ['id' => 1, 'name' => 'Hà Nội'],
            ['id' => 2, 'name' => 'Hồ Chí Minh'],
        ]);

        // Districts (Quận/Huyện)
        DB::table('districts')->insert([
            // Quận thuộc Hà Nội
            ['id' => 1, 'province_id' => 1, 'name' => 'Ba Đình'],
            ['id' => 2, 'province_id' => 1, 'name' => 'Hoàn Kiếm'],

            // Quận thuộc TP Hồ Chí Minh
            ['id' => 3, 'province_id' => 2, 'name' => 'Quận 1'],
            ['id' => 4, 'province_id' => 2, 'name' => 'Quận 3'],
        ]);

        // Wards (Phường/Xã)
        DB::table('wards')->insert([
            // Phường thuộc Ba Đình (Hà Nội)
            ['id' => 1, 'district_id' => 1, 'name' => 'Phường Điện Biên'],
            ['id' => 2, 'district_id' => 1, 'name' => 'Phường Kim Mã'],

            // Phường thuộc Hoàn Kiếm (Hà Nội)
            ['id' => 3, 'district_id' => 2, 'name' => 'Phường Hàng Bạc'],
            ['id' => 4, 'district_id' => 2, 'name' => 'Phường Hàng Gai'],

            // Phường thuộc Quận 1 (TPHCM)
            ['id' => 5, 'district_id' => 3, 'name' => 'Phường Bến Nghé'],
            ['id' => 6, 'district_id' => 3, 'name' => 'Phường Bến Thành'],

            // Phường thuộc Quận 3 (TPHCM)
            ['id' => 7, 'district_id' => 4, 'name' => 'Phường Võ Thị Sáu'],
            ['id' => 8, 'district_id' => 4, 'name' => 'Phường 7'],
        ]);
    }
}
