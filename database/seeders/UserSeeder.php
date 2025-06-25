<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào bảng Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),  // Mã hóa mật khẩu
            'phone_number' => '1234567890',
            'image_profile' => 'profile_image.jpg',
            'is_active' => true,
            'birthday' => '1990-01-01',
            'gender' => 'Male',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            'phone_number' => '0987654321',
            'image_profile' => 'profile_image2.jpg',
            'is_active' => true,
            'birthday' => '1992-02-02',
            'gender' => 'Female',
        ]);

        User::create([
            'name' => 'Alice Nguyen',
            'email' => 'alice@example.com',
            'password' => bcrypt('alicepassword'),
            'phone_number' => '0911222333',
            'image_profile' => 'profile_image3.jpg',
            'is_active' => true,
            'birthday' => '1995-03-15',
            'gender' => 'Female',
        ]);

        User::create([
            'name' => 'Bob Tran',
            'email' => 'bob@example.com',
            'password' => bcrypt('bobpassword'),
            'phone_number' => '0901234567',
            'image_profile' => 'profile_image4.jpg',
            'is_active' => true,
            'birthday' => '1988-04-10',
            'gender' => 'Male',
        ]);

        User::create([
            'name' => 'Charlie Le',
            'email' => 'charlie@example.com',
            'password' => bcrypt('charliepassword'),
            'phone_number' => '0934567890',
            'image_profile' => 'profile_image5.jpg',
            'is_active' => true,
            'birthday' => '1991-05-20',
            'gender' => 'Male',
        ]);

        User::create([
            'name' => 'David Pham',
            'email' => 'david@example.com',
            'password' => bcrypt('davidpassword'),
            'phone_number' => '0976543210',
            'image_profile' => 'profile_image6.jpg',
            'is_active' => true,
            'birthday' => '1985-06-30',
            'gender' => 'Male',
        ]);

        User::create([
            'name' => 'Emily Vo',
            'email' => 'emily@example.com',
            'password' => bcrypt('emilypassword'),
            'phone_number' => '0922334455',
            'image_profile' => 'profile_image7.jpg',
            'is_active' => true,
            'birthday' => '1993-07-12',
            'gender' => 'Female',
        ]);

        User::create([
            'name' => 'Frank Nguyen',
            'email' => 'frank@example.com',
            'password' => bcrypt('frankpassword'),
            'phone_number' => '0944556677',
            'image_profile' => 'profile_image8.jpg',
            'is_active' => true,
            'birthday' => '1989-08-25',
            'gender' => 'Male',
        ]);

        User::create([
            'name' => 'Grace Hoang',
            'email' => 'grace@example.com',
            'password' => bcrypt('gracepassword'),
            'phone_number' => '0966778899',
            'image_profile' => 'profile_image9.jpg',
            'is_active' => true,
            'birthday' => '1996-09-18',
            'gender' => 'Female',
        ]);

        User::create([
            'name' => 'Henry Bui',
            'email' => 'henry@example.com',
            'password' => bcrypt('henrypassword'),
            'phone_number' => '0911888999',
            'image_profile' => 'profile_image10.jpg',
            'is_active' => true,
            'birthday' => '1994-10-22',
            'gender' => 'Male',
        ]);

        User::create([
            'name' => 'Ivy Dang',
            'email' => 'ivy@example.com',
            'password' => bcrypt('ivypassword'),
            'phone_number' => '0933445566',
            'image_profile' => 'profile_image11.jpg',
            'is_active' => true,
            'birthday' => '1997-11-05',
            'gender' => 'Female',
        ]);

        User::create([
            'name' => 'Jackie Lam',
            'email' => 'jackie@example.com',
            'password' => bcrypt('jackiepassword'),
            'phone_number' => '0955667788',
            'image_profile' => 'profile_image12.jpg',
            'is_active' => true,
            'birthday' => '1998-12-14',
            'gender' => 'Male',
        ]);

        User::create([
            'name' => 'Kim Chi',
            'email' => 'kimchi@example.com',
            'password' => bcrypt('kimchipassword'),
            'phone_number' => '0988776655',
            'image_profile' => 'profile_image13.jpg',
            'is_active' => true,
            'birthday' => '1993-03-03',
            'gender' => 'Female',
        ]);

        // Thêm các bản ghi thực tế khác nếu cần
        User::factory()->count(10)->create();
    }
}
