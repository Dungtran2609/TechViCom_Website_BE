<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
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
        'viewed_at',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng đã xem sản phẩm)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với bảng Product (Sản phẩm đã được xem)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
