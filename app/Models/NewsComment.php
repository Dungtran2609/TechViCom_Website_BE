<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'news_id',
        'content',
        'is_hidden',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng đã viết bình luận)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mối quan hệ với bảng News (Bài viết mà bình luận này thuộc về)
     */
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
