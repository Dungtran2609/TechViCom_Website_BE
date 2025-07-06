<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_variants_attributes'; 

    protected $fillable = [
        'product_variant_id',
        'attribute_value_id',
    ];

    /**
     * Mối quan hệ với biến thể sản phẩm
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Mối quan hệ với giá trị thuộc tính (value)
     */
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
