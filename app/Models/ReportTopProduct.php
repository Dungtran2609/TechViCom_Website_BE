<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTopProduct extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'sold_quantity',
        'report_date',
    ];

    /**
     * Mối quan hệ với bảng Product (Sản phẩm bán chạy)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
