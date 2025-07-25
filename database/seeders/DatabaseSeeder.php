<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([ 
            UserSeeder::class,
            CategorySeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,
            LoginHistorySeeder::class,
            ProductSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            UserRoleSeeder::class,
            PermissionRoleSeeder::class,
            BrandSeeder::class,
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            ContactSeeder::class,
            ProductVariantSeeder::class,
            ProductVariantAttributeSeeder::class,
            CouponSeeder::class, 
        ]);
    }
}
