<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('coupons')->insert([
            [
                'code'                => 'DISCOUNT10',      // Mã coupon
                'discount_type'       => 'percentage',      // Loại giảm giá: 'percentage' = phần trăm
                'value'               => 10.00,             // Giá trị giảm giá: 10%
                'max_discount_amount' => 100000.00,         // Giảm tối đa 100,000 VND
                'min_order_value'     => 500000.00,         // Đơn tối thiểu để dùng: 500,000 VND
                'max_order_value'     => 5000000.00,        // Đơn tối đa áp dụng: 5,000,000 VND
                'max_usage_per_user'  => 5,                 // Mỗi user dùng tối đa 5 lần
                'start_date'          => now()->subDays(10),// Bắt đầu cách đây 10 ngày
                'end_date'            => now()->addMonths(1),// Kết thúc sau 1 tháng
                'status'              => true,              // Đang kích hoạt
            ],
        ]);
    }
}
