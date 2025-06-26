<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news')->insert([
            [
                'title' => 'Giới thiệu sản phẩm mới',
                'content' => 'Chúng tôi hân hạnh giới thiệu sản phẩm mới nhất của năm 2025...',
                'author_id' => 1, // Giả sử user_id = 1 tồn tại
                'status' => 'published',
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'Khuyến mãi lớn dịp hè',
                'content' => 'Đừng bỏ lỡ cơ hội sở hữu sản phẩm với mức giá ưu đãi...',
                'author_id' => 1,
                'status' => 'draft',
                'published_at' => null,
            ],
            [
                'title' => 'Cập nhật chính sách bảo hành',
                'content' => 'Chính sách bảo hành mới sẽ mang lại nhiều quyền lợi hơn cho khách hàng...',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
            ],
        ]);
    }
}
