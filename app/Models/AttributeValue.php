<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'attribute_id',
        'name',
    ];

    /**
     * Mối quan hệ với bảng Attribute (Thuộc tính mà giá trị này thuộc về)
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Mối quan hệ với bảng ProductVariantsAttribute (Các sản phẩm biến thể có thuộc tính này)
     */
    public function productVariantsAttribute()
    {
        return $this->hasMany(ProductVariantsAttribute::class);
    }
}
