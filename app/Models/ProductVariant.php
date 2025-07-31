<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'weight',
        'dimensions',
        'stock',
        'image',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng Product (Sản phẩm mà biến thể này thuộc về)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với bảng AttributeValue (Giá trị thuộc tính của biến thể)
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variants_attributes', 'product_variant_id', 'attribute_value_id')
            ->withTimestamps();
    }

    /**
     * Mối quan hệ với bảng WarehouseInventory (Kho của các biến thể sản phẩm)
     */
    public function warehouseInventories()
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    /**
     * Mối quan hệ với bảng ProductAllImage (Hình ảnh của biến thể sản phẩm)
     */
    public function productAllImages()
    {
        return $this->hasMany(ProductAllImage::class, 'variant_id');
    }
}
