<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('shipping_methods')->insert([
    [
        'id'             => 1,
        'name'           => 'Giao tận nơi',
        'description'    => 'Dự kiến giao hàng trong 1-2 ngày làm việc',
        'fee'            => 30000.00,
        'estimated_days' => 2,
        'max_weight'     => 10000,
        'regions'        => 'Hà Nội',
        'ghn_service_id' => 53320,
        'created_at'     => $now,
        'updated_at'     => $now,
    ],
    [
        'id'             => 2,
        'name'           => 'Nhận tại cửa hàng',
        'description'    => 'Dự kiến giao hàng trong 1-2 ngày làm việc',
        'fee'            => 20000.00,
        'estimated_days' => 5,
        'max_weight'     => 5000, // cần có đủ
        'regions'        => 'Hà Nội',
        'ghn_service_id' => 53321,
        'created_at'     => $now,
        'updated_at'     => $now,
    ]
]);

    }
}