<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $timestamps = false; // Nếu bảng không có created_at, updated_at

    protected $table = 'news';

    protected $primaryKey = 'news_id';

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
     * Bài viết thuộc về một danh mục
     */
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id', 'category_id');
    }
}
