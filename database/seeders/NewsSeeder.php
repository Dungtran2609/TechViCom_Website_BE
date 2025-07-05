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
                'category_id'=> '1',
                'title' => 'Giới thiệu sản phẩm mới',
                'content' => 'Chúng tôi hân hạnh giới thiệu sản phẩm mới nhất của năm 2025...',
                'image'=> 'https://picsum.photos/100',
                'author_id' => 1, 
                'status' => 'published',
                'published_at' => Carbon::now(),
            ],
            [
                'category_id'=> '2',
                'title' => 'Khuyến mãi lớn dịp hè',
                'content' => 'Đừng bỏ lỡ cơ hội sở hữu sản phẩm với mức giá ưu đãi...',
                'image'=> 'https://picsum.photos/100',
                'author_id' => 1,
                'status' => 'draft',
                'published_at' => Carbon::now(),
            ],
            [
                'category_id'=> '3',
                'title' => 'Cập nhật chính sách bảo hành',
                'content' => 'Chính sách bảo hành mới sẽ mang lại nhiều quyền lợi hơn cho khách hàng...',
                'image'=> 'https://picsum.photos/100',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
            ],
        ]);
    }
}


