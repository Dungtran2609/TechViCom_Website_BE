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
    // app/Models/Product.php

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    // 👉 Quan hệ tới các biến thể sản phẩm
    public function getDisplayPriceAttribute()
{
    if ($this->type === 'simple') {
        return $this->price
            ? number_format($this->price, 0, ',', '.') . ' đ'
            : 'Chưa có giá';
    }

    if ($this->variants->count()) {
        $min = $this->variants->min('price');
        $max = $this->variants->max('price');

        return ($min && $max)
            ? 'Từ ' . number_format($min, 0, ',', '.') . ' đ - ' . number_format($max, 0, ',', '.') . ' đ'
            : 'Chưa có giá';
    }

    return 'Chưa có giá';
}


}