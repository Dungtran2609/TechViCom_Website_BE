<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
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
        'content',
        'is_hidden',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng đã bình luận)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với bảng Product (Sản phẩm mà bình luận này thuộc về)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
