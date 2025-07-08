<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'user_id'            => 1,
                'address_id'         => 1,
                'payment_method'     => 'credit_card',
                'status'             => 'processing',
                'total_amount'       => 1000000.00, // Giá trị mẫu cho tổng tiền sản phẩm
                'final_total'        => 1100000.00, // Giá trị mẫu sau khi cộng phí vận chuyển và trừ giảm giá
                // 'shipping_fee'       => 50000.00,   // Phí vận chuyển mẫu
                
                'shipping_method_id' => 1,          // ID của shipping_method (giả sử đã có trong DB)
                'coupon_id'          => 1,          // ID của coupon (giả sử đã có trong DB)
                'recipient_name'     => 'Nguyễn Văn A',
                'recipient_phone'    => '0901234567',
                'recipient_address'  => '123 Đường ABC, Quận 1, TP.HCM',
                'shipped_at'         => Carbon::now()->addDays(2),
                'created_at'         => now(),
                'updated_at'         => now(),
                'deleted_at'         => null, // Không xóa mềm ban đầu
            ],
            [
                'user_id'            => 2,
                'address_id'         => 2,
                'payment_method'     => 'cod',
                'status'             => 'pending',
                'total_amount'       => 800000.00, // Giá trị mẫu cho tổng tiền sản phẩm
                'final_total'        => 850000.00, // Giá trị mẫu sau khi cộng phí vận chuyển và trừ giảm giá
                // 'shipping_fee'       => 50000.00,  // Phí vận chuyển mẫu
               
                'shipping_method_id' => 2,         // ID của shipping_method (giả sử đã có trong DB)
                'coupon_id'          => 1,      // Không áp dụng coupon
                'recipient_name'     => 'Trần Thị B',
                'recipient_phone'    => '0912345678',
                'recipient_address'  => '456 Đường XYZ, Quận 7, TP.HCM',
                'shipped_at'         => null,
                'created_at'         => now(),
                'updated_at'         => now(),
                'deleted_at'         => null, // Không xóa mềm ban đầu
            ],
        ]);
    }
}
