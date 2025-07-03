<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    // Cho phép gán các trường này
    protected $fillable = [
        'id',
        'title',
        'content',
        'author_id',
        'status',
        'published_at',
    ];

    

    // Nếu không dùng timestamps mặc định
    public $timestamps = false;
}
