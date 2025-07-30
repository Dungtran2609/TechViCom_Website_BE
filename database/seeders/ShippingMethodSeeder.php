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
                'name'           => 'GHN - Giao hàng nhanh',
                'description'    => 'Dự kiến giao hàng trong 1-2 ngày làm việc',
                // 'fee'         => null, // nếu dùng API tính phí động thì không cần lưu ở đây
                'estimated_days' => 2,
                'max_weight'     => 10000, // đơn vị gram (10kg)
                'regions'        => 'Toàn quốc',
                'ghn_service_id' => 53320, // Mã dịch vụ GHN (ví dụ GHN Express)
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            // Bạn có thể thêm nhiều phương thức ở đây nếu cần:
            /*
            [
                'id'             => 2,
                'name'           => 'GHN - Tiết kiệm',
                'description'    => 'Giao hàng tiết kiệm trong 3-5 ngày',
                'estimated_days' => 5,
                'max_weight'     => 20000,
                'regions'        => 'Toàn quốc',
                'ghn_service_id' => 53321,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]
            */
        ]);
    }
}