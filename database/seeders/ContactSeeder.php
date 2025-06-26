<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contacts')->insert([
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'vana@example.com',
                'phone' => '0912345678',
                'subject' => 'Tư vấn sản phẩm',
                'message' => 'Xin chào, tôi cần tư vấn về sản phẩm rau sạch.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Thị B',
                'email' => 'vana@example.com',
                'phone' => '0988123456',
                'subject' => 'Phản hồi dịch vụ',
                'message' => 'Dịch vụ rất tốt, tôi rất hài lòng.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Văn C',
                'email' => 'ivan@example.com',
                'phone' => '0912345678',
                'subject' => 'Đặt hàng',
                'message' => 'Tôi muốn đặt hàng một số sản phẩm rau sạch.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
