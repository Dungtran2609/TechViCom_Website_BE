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
            CategorySeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,
            LoginHistorySeeder::class,
            ProductSeeder::class,

            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
            PermissionRoleSeeder::class,


            // Thông báo
            NotificationSeeder::class,
            UserNotificationSeeder::class,

            // Cấu trúc sản phẩm
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,

            // Thuộc tính sản phẩm
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            ProductVariantAttributeSeeder::class,

            // Tag & comment & view
            TagSeeder::class,
            ProductTagSeeder::class,
            ProductCommentSeeder::class,
            ProductViewSeeder::class,
            ProductAllImageSeeder::class,

            // Kho
            WarehouseSeeder::class,
            WarehouseInventorySeeder::class,

            // Địa chỉ, giao hàng, voucher
            UserAddressSeeder::class,
            ShippingMethodSeeder::class,
            CouponSeeder::class,

            // Đơn hàng
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderReturnSeeder::class,

            // Giao dịch
            TransactionSeeder::class,

            // Wishlist
            WishlistSeeder::class,

            // Bài viết
            NewsSeeder::class,
            NewsCommentSeeder::class,

            // Báo cáo, cài đặt
            ActivityLogSeeder::class,
            ReportTopProductSeeder::class,
            ReportSalesDailySeeder::class,
            ReportUserActivitySeeder::class,
            SettingSeeder::class,

            // Banner
            BannerSeeder::class,

            // Lịch sử đăng nhập
            LoginHistorySeeder::class,






        ]);
    }
}
