<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'tag_id',
    ];

    /**
     * Mối quan hệ với bảng Product (Sản phẩm có gắn thẻ này)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với bảng Tag (Thẻ được gắn vào sản phẩm này)
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
