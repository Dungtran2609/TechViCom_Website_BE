<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'sku',
        'price',
        'discount_price',
        'weight',
        'dimensions',
        'stock',
        'description',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];

    /**
     * Mối quan hệ với bảng Category (Danh mục của sản phẩm)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mối quan hệ với bảng Brand (Thương hiệu của sản phẩm)
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Mối quan hệ với bảng User (Người tạo sản phẩm)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Mối quan hệ với bảng ProductVariant (Các biến thể của sản phẩm)
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Mối quan hệ với bảng ProductTag (Các thẻ của sản phẩm)
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
}
