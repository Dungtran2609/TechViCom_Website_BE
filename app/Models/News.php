<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NewsComment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class News extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'news';
    protected $primaryKey = 'id'; // ✅ Khai báo lại khoá chính
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'content',
        'image',
        'author_id',
        'category_id',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id');
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id', 'category_id');
    }

    public function visibleComments()
    {
        return $this->hasMany(NewsComment::class)->where('is_hidden', false);
    }

    public function likes(): MorphMany
{
    return $this->morphMany(Like::class, 'likeable');
}
}
