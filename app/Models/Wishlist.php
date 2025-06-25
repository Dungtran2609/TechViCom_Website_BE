<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
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
        'product_variant_id',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng sở hữu danh sách yêu thích này)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với bảng Product (Sản phẩm trong danh sách yêu thích)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với bảng ProductVariant (Biến thể sản phẩm trong danh sách yêu thích)
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
