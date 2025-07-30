<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $startDate = $now->copy()->subDays(1);
        $endDate = $now->copy()->addDays(30);

        $coupons = [
            [
                'code' => 'SALE50',
                'discount_type' => 'percent',
                'value' => 50,
                'max_discount_amount' => 10000,
                'min_order_value' => 200000,
                'max_order_value' => 1000000,
                'max_usage_per_user' => 2,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 1,
            ],
            [
                'code' => 'SALE100',
                'discount_type' => 'percent',
                'value' => 50,
                'max_discount_amount' => 100000,
                'min_order_value' => 200000,
                'max_order_value' => 1000000,
                'max_usage_per_user' => 2,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 1,
            ],
            [
                'code' => 'SALESMALL',
                'discount_type' => 'percent',
                'value' => 50,
                'max_discount_amount' => 200,
                'min_order_value' => 0,
                'max_order_value' => 100,
                'max_usage_per_user' => 1,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 1,
            ],

        ];

        foreach ($coupons as $coupon) {
            DB::table('coupons')->updateOrInsert(
                ['code' => $coupon['code']],
                $coupon
            );
        }

    }
}