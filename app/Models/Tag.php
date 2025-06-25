<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Mối quan hệ với bảng Product (Sản phẩm mà thẻ này được gắn)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }
}
