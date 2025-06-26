<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
    ];

    /**
     * Mối quan hệ với bảng Category (Danh mục cha)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Mối quan hệ với các bảng Product (Sản phẩm thuộc danh mục này)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
