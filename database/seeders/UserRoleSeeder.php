<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_roles')->insert([
            // User 1: admin, editor, user
            ['user_id' => 1, 'role_id' => 1], // admin
            ['user_id' => 1, 'role_id' => 2], // editor
            ['user_id' => 1, 'role_id' => 3], // user

            // User 2-10: user
            ['user_id' => 2, 'role_id' => 3],
            ['user_id' => 3, 'role_id' => 3],
            ['user_id' => 4, 'role_id' => 3],
            ['user_id' => 5, 'role_id' => 3],
            ['user_id' => 6, 'role_id' => 3],
            ['user_id' => 7, 'role_id' => 3],
            ['user_id' => 8, 'role_id' => 3],
            ['user_id' => 9, 'role_id' => 3],
            ['user_id' => 10, 'role_id' => 3],

            // Gán quyền admin cho user admin@gmail.com (id = 13)
            ['user_id' => 13, 'role_id' => 1], // admin
        ]);
    }
}



