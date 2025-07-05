<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'variant_id',
        'quantity',
        'product_id',
       
    ];

    /**
     * Mối quan hệ với bảng Order (Đơn hàng chứa sản phẩm này)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mối quan hệ với bảng ProductVariant (Biến thể sản phẩm trong đơn hàng)
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Mối quan hệ với bảng Product (Sản phẩm trong đơn hàng)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với bảng ProductAllImage qua ProductVariant
     */
    public function productAllImages()
    {
        return $this->hasManyThrough(
            ProductAllImage::class, // Model đích
            ProductVariant::class,  // Model trung gian
            'id',                   // Khóa chính của ProductVariant
            'variant_id',           // Khóa ngoại trong ProductAllImage liên kết với ProductVariant
            'variant_id'            // Khóa ngoại trong OrderItem liên kết với ProductVariant
        );
    }
}
