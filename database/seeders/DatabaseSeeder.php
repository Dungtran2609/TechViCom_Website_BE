<?php
namespace Database\Seeders;

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
            RoleSeeder::class,
            PermissionSeeder::class,
            UserRoleSeeder::class,
            PermissionRoleSeeder::class,

            CategorySeeder::class,
            BrandSeeder::class,

            NewsCategorySeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,

            ProductSeeder::class,
            ProductVariantSeeder::class,

            AttributeSeeder::class,
            AttributeValueSeeder::class,
            ProductVariantAttributeSeeder::class,

            TagSeeder::class,
            ProductTagSeeder::class,
            ProductCommentSeeder::class,
            ProductViewSeeder::class,
            ProductAllImageSeeder::class,

            WarehouseSeeder::class,
            WarehouseInventorySeeder::class,

            UserAddressSeeder::class,
            ShippingMethodSeeder::class,
            CouponSeeder::class,

            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderReturnSeeder::class,

            TransactionSeeder::class,
            WishlistSeeder::class,

            ActivityLogSeeder::class,
            ReportTopProductSeeder::class,
            ReportSalesDailySeeder::class,
            ReportUserActivitySeeder::class,
            SettingSeeder::class,

            NotificationSeeder::class,
            UserNotificationSeeder::class,

            BannerSeeder::class,
            LoginHistorySeeder::class,
        ]);

    }
}
