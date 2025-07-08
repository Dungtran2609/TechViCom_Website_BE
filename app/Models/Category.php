<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'image',
        'status',
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
