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
    {
        DB::table('order_returns')->insert([
            [
                'order_id'     => 1,
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
                'order_id'     => 2,
                'reason'       => 'Khách đổi ý không muốn nhận nữa',
                'status'       => 'approved',
                'type'         => 'cancel',
                'requested_at' => Carbon::now()->subDays(3),
                'processed_at' => Carbon::now()->subDays(1),
                'admin_note'   => 'Đơn đã hủy, hoàn tiền 100%',
                'created_at'   => Carbon::now()->subDays(3),
                'updated_at'   => Carbon::now()->subDays(1),
            ],
            [
                'order_id'     => 1,
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
