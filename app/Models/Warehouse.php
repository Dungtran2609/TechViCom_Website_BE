<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng WarehouseInventory (Kho của các biến thể sản phẩm)
     */
    public function inventory()
    {
        return $this->hasMany(WarehouseInventory::class);
    }
}
