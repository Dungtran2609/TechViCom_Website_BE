<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng Product (Các sản phẩm thuộc thương hiệu này)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
