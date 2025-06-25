<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseInventory extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'warehouse_id',
        'product_variant_id',
        'stock',
    ];

    /**
     * Mối quan hệ với bảng Warehouse (Kho mà sản phẩm biến thể này thuộc về)
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Mối quan hệ với bảng ProductVariant (Biến thể sản phẩm trong kho)
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
