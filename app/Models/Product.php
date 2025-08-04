<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'type',
        'price',
        'sale_price',
        'stock',
        'low_stock_amount',
        'short_description',
        'long_description',
        'thumbnail',
        'status',
        'brand_id',
        'category_id',
        'is_featured',
        'view_count',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',
        'stock' => 'integer',
    ];

    // 👉 Quan hệ tới thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // 👉 Quan hệ tới danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 👉 Danh sách tất cả ảnh phụ
    public function allImages()
    {
        return $this->hasMany(ProductAllImage::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    // 👉 Quan hệ tới các biến thể sản phẩm
    public function getDisplayPriceAttribute()
{
    if ($this->type === 'simple') {
        return $this->sale_price && $this->sale_price < $this->price
            ? $this->sale_price
            : $this->price;
    }
    if ($this->variants->count()) {
        $min = $this->variants->min('price');
        $max = $this->variants->max('price');
        return ($min && $max && $min != $max)
            ? ['min' => $min, 'max' => $max]
            : $min;
    }
    return null;
}
        public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }
}