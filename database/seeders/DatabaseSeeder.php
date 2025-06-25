<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Gọi tất cả các Seeder đã tạo
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
            NotificationSeeder::class,
            UserNotificationSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            TagSeeder::class,              // <-- Đặt trước ProductTagSeeder
            ProductTagSeeder::class,       // <-- Đặt sau TagSeeder
            ProductCommentSeeder::class,
            ProductViewSeeder::class,
            UserAddressSeeder::class, // <-- Đặt trước OrderSeeder
            OrderSeeder::class,
            OrderItemSeeder::class,
            CouponSeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,
            ActivityLogSeeder::class,
            ReportTopProductSeeder::class,
            ReportSalesDailySeeder::class,
            ReportUserActivitySeeder::class,
            SettingSeeder::class,
            ShippingMethodSeeder::class,
            TransactionSeeder::class,
            WishlistSeeder::class,
            WarehouseSeeder::class,
            WarehouseInventorySeeder::class,
            ProductAllImageSeeder::class,
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            ProductVariantsAttributeSeeder::class,
            LoginHistorySeeder::class,
            BannerSeeder::class,
        ]);
    }
}
