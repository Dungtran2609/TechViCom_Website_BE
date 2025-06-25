<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment_text',
        'is_approved',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng đã viết đánh giá)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với bảng Product (Sản phẩm được đánh giá)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
