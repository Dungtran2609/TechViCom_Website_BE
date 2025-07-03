<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();


        // Lấy danh sách các ID từ bảng users
        $users = DB::table('users')->pluck('id'); // Thay user_id thành id


        foreach ($users as $userId) {
            // Tạo một địa chỉ mặc định
            DB::table('user_addresses')->insert([
                'user_id' => $userId, // Tham chiếu cột id trong bảng users
                'address_line' => $faker->address(),
                'ward' => 'Phường ' . $faker->numberBetween(1, 20),
                'district' => 'Quận ' . $faker->numberBetween(1, 10),
                'city' => 'Thành phố ' . $faker->numberBetween(1, 5),
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // Tạo thêm 2 địa chỉ phụ
            for ($i = 0; $i < 2; $i++) {
                DB::table('user_addresses')->insert([
                    'user_id' => $userId, // Tham chiếu cột id trong bảng users
                    'address_line' => $faker->address(),
                    'ward' => 'Xã/Phường ' . $faker->numberBetween(1, 20),
                    'district' => 'Quận/Huyện ' . $faker->numberBetween(1, 10),
                    'city' => 'Thành phố ' . $faker->numberBetween(1, 5),
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
