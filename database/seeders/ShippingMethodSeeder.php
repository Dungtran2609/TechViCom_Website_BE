<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shipping_methods')->insert([
            [
                'name'           => 'Giao hàng nhanh',
                'description'    => 'Giao hàng trong 1-2 ngày',
                'fee'            => 50000.00,
                'estimated_days' => 2,
                'max_weight'     => 10.00,
                'regions'        => 'Toàn quốc',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Giao hàng tiết kiệm',
                'description'    => 'Giao hàng trong 3-5 ngày',
                'fee'            => 30000.00,
                'estimated_days' => 4,
                'max_weight'     => 15.00,
                'regions'        => 'Toàn quốc',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
