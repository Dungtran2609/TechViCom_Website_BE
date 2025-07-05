<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAllImage extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'variant_id',
        'img_url',
        'is_primary',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng Product (Sản phẩm mà hình ảnh này thuộc về)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với bảng ProductVariant (Biến thể sản phẩm mà hình ảnh này thuộc về)
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
