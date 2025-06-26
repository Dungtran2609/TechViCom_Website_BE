<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    public $timestamps = false; // Nếu bảng không có created_at, updated_at 

    protected $table = 'news';

    protected $primaryKey = 'news_id';

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'image',
        'author_id',
        'category_id', // Thêm dòng này để fillable category_id
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Mối quan hệ với bảng User (Tác giả)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Mối quan hệ với bảng NewsComment
     */
    public function comments()
    {
        return $this->hasMany(NewsComment::class);
    }

    /**
     * Bài viết thuộc về một danh mục
     */
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id', 'category_id');
    }
}
