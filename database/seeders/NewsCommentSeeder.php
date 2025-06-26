<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsComment;
use App\Models\User;
use App\Models\News;

class NewsCommentSeeder extends Seeder
{
    public function run()
    {
        // 1. Tạo sẵn 2 người dùng (nếu chưa có)
        $user1 = User::firstOrCreate(['id' => 1], [
            'name' => 'Nguyễn Văn A',
            'email' => 'a@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $user2 = User::firstOrCreate(['id' => 2], [
            'name' => 'Trần Thị B',
            'email' => 'b@example.com',
            'password' => bcrypt('12345678'),
        ]);

        // 2. Tạo sẵn 2 bài viết (nếu chưa có)
        $news1 = News::firstOrCreate(['news_id' => 1], [
            'title' => 'Tin tức công nghệ AI',
            'content' => 'Nội dung bài viết về AI...',
            'author_id' => $user1->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $news2 = News::firstOrCreate(['news_id' => 2], [
            'title' => 'Xu hướng Laravel 2025',
            'content' => 'Nội dung bài viết về Laravel...',
            'author_id' => $user2->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 3. Tạo bình luận thực tế
        NewsComment::create([
            'user_id' => $user1->id,
            'news_id' => $news1->news_id,
            'content' => 'Bài viết rất hay, cảm ơn tác giả!',
            'is_hidden' => false,
        ]);

        NewsComment::create([
            'user_id' => $user2->id,
            'news_id' => $news2->news_id,
            'content' => 'Thông tin rất hữu ích, mong có thêm bài mới!',
            'is_hidden' => false,
        ]);

        // 4. Tạo thêm 3 bình luận ngẫu nhiên
        NewsComment::factory()->count(3)->create();
    }
}
