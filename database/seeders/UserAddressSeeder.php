<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy danh sách các ID từ bảng users
        $users = DB::table('users')->pluck('id');

        // Lấy danh sách id từ bảng provinces, districts, wards
        $provinceIds = DB::table('provinces')->pluck('id');
        $districtIds = DB::table('districts')->pluck('id');
        $wardIds     = DB::table('wards')->pluck('id');

        foreach ($users as $userId) {
            // Tạo một địa chỉ mặc định
            DB::table('user_addresses')->insert([
                'user_id'      => $userId,
                'address_line' => $faker->streetAddress(),
                'province_id'  => $faker->randomElement($provinceIds),
                'district_id'  => $faker->randomElement($districtIds),
                'ward_id'      => $faker->randomElement($wardIds),
                'is_default'   => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // Tạo thêm 2 địa chỉ phụ
            for ($i = 0; $i < 2; $i++) {
                DB::table('user_addresses')->insert([
                    'user_id'      => $userId,
                    'address_line' => $faker->streetAddress(),
                    'province_id'  => $faker->randomElement($provinceIds),
                    'district_id'  => $faker->randomElement($districtIds),
                    'ward_id'      => $faker->randomElement($wardIds),
                    'is_default'   => false,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }
    }
}
