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
                'image'=> 'uploads/news/1751647722_686805ea3dbb2.jpg',
                'author_id' => 1, 
                'status' => 'published',
                'published_at' => Carbon::now(),
            ],
            [
                'category_id'=> '2',
                'title' => 'Khuyến mãi lớn dịp hè',
                'content' => 'Đừng bỏ lỡ cơ hội sở hữu sản phẩm với mức giá ưu đãi...',
                'image'=> 'uploads/news/1751803457_Remove-bg.ai_1728938373937.png',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => Carbon::now(),
            ],
            [
                'category_id'=> '3',
                'title' => 'Cập nhật chính sách bảo hành',
                'content' => 'Chính sách bảo hành mới sẽ mang lại nhiều quyền lợi hơn cho khách hàng...',
                'image'=> 'uploads/news/1754049854_688cad3eec594.jpg',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'category_id'=> '1',
                'title' => 'Cậu bé 13 tuổi ở TPHCM làm \'giám đốc\': Phụ huynh hé lộ cuộc sống đời thường',
                'content' => 'Câu chuyện thú vị về một cậu bé 13 tuổi ở TP.HCM đã trở thành "giám đốc" của một công ty...',
                'image'=> 'uploads/news/1754049916_688cad7c53c65.png',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(1),
            ],
        ]);
    }
}


