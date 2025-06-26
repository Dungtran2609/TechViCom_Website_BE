<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantsAttribute extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'product_variant_id',
        'attribute_value_id',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng ProductVariant (Biến thể sản phẩm mà thuộc tính này thuộc về)
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Mối quan hệ với bảng AttributeValue (Giá trị thuộc tính mà biến thể sản phẩm này có)
     */
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
