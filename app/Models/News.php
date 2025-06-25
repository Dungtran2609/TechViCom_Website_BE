<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'status',
        'published_at',
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
}
