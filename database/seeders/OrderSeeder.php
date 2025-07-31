<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();
        $orders = [];

        foreach ($users as $user) {
            $address = DB::table('user_addresses')
                ->where('user_id', $user->id)
                ->where('is_default', true)
                ->first();

            if (!$address) {
                continue;
            }

            $totalAmount = 500000; // bạn có thể random nếu muốn
            $totalWeight = 1.5;    // kg

            // 👉 Tính phí ship: nếu >= 3 triệu thì 0, ngược lại 60k
            $shippingFee = $totalAmount >= 3000000 ? 0 : 60000;

            $orders[] = [
                'user_id' => $user->id,
                'address_id' => $address->id,
                'recipient_name' => $user->name,
                'recipient_phone' => $user->phone_number,
                'recipient_address' => $address->address_line,
                'province_id' => 1, // Hà Nội
                'district_id' => 1, // Hà Nội
                'ward_id' => 1, // Hà Nội
                'total_amount' => $totalAmount,
                'total_weight' => $totalWeight,
                'coupon_id' => 1,
                'payment_method' => 'cod',
                'status' => 'processing',
                'shipping_fee' => $shippingFee,
                'final_total' => $totalAmount + $shippingFee,
                'shipping_method_id' => 1, // Giữ nguyên nếu có sẵn
                'shipped_at' => Carbon::now()->addDays(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ];
        }

        DB::table('orders')->insert($orders);
    }
}
