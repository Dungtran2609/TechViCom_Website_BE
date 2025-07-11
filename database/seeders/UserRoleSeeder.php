<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Kiểm tra xem user và role có tồn tại không trước khi insert
        $users = DB::table('users')->whereIn('id', [1, 2, 3])->pluck('id');
        $roles = DB::table('roles')->whereIn('id', [1, 2, 3])->pluck('id');

        $userRoles = [];

        if ($users->contains(1) && $roles->contains(1)) {
            $userRoles[] = ['user_id' => 1, 'role_id' => 1]; // user 1 là admin
        }

        if ($users->contains(2) && $roles->contains(2)) {
            $userRoles[] = ['user_id' => 2, 'role_id' => 2]; // user 2 là editor
        }

        if ($users->contains(3) && $roles->contains(3)) {
            $userRoles[] = ['user_id' => 3, 'role_id' => 3]; // user 3 là user
        }
if ($users->contains(3) && $roles->contains(3)) {
            $userRoles[] = ['user_id' => 13, 'role_id' => 1]; 
        }
        if (!empty($userRoles)) {
            DB::table('user_roles')->insert($userRoles);
        }
    }
}
