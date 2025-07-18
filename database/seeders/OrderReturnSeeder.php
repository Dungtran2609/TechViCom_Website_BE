<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {$order = DB::table('orders')->orderBy('id')->first();
if (! $order) {
    throw new \Exception('Không có đơn hàng nào để tạo order_return!');
}

        DB::table('order_returns')->insert([
            [
                'order_id'    => $order->id,
                'reason'       => 'Sản phẩm bị lỗi khi nhận',
                'status'       => 'pending',
                'type'         => 'return',
                'requested_at' => now(),
                'processed_at' => null,
                'admin_note'   => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'order_id'    => $order->id,
                'reason'       => 'Sai mẫu mã so với đặt hàng',
                'status'       => 'rejected',
                'type'         => 'return',
                'requested_at' => Carbon::now()->subDays(5),
                'processed_at' => Carbon::now()->subDays(4),
                'admin_note'   => 'Không chấp nhận trả hàng do khách sai yêu cầu',
                'created_at'   => Carbon::now()->subDays(5),
                'updated_at'   => Carbon::now()->subDays(4),
            ],
        ]);
    }
}
