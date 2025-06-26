<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
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
        'quantity',
        'created_at',
        'updated_at',
        'product_variant_id',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng sở hữu giỏ hàng này)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với bảng Product (Sản phẩm trong giỏ hàng)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với bảng ProductVariant (Biến thể sản phẩm trong giỏ hàng)
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
