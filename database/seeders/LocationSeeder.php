<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run()
    {
        DB::table('provinces')->insert([
            ['id' => 1, 'name' => 'Hà Nội'],
            ['id' => 2, 'name' => 'Hồ Chí Minh'],
        ]);

        DB::table('districts')->insert([
            ['id' => 1, 'province_id' => 1, 'name' => 'Ba Đình'],
            ['id' => 2, 'province_id' => 1, 'name' => 'Hoàn Kiếm'],
            ['id' => 3, 'province_id' => 2, 'name' => 'Quận 1'],
        ]);

        DB::table('wards')->insert([
            ['id' => 1, 'district_id' => 1, 'name' => 'Phường Điện Biên'],
            ['id' => 2, 'district_id' => 2, 'name' => 'Phường Hàng Bạc'],
            ['id' => 3, 'district_id' => 3, 'name' => 'Phường Bến Nghé'],
        ]);
    }
}
