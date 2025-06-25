<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'discount_type',
        'value',
        'max_discount_amount',
        'min_order_value',
        'max_order_value',
        'max_usage_per_user',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Hàm kiểm tra xem coupon có còn hiệu lực hay không
     */
    public function isValid()
    {
        $now = now();
        return $this->status && $now->between($this->start_date, $this->end_date);
    }

    /**
     * Mối quan hệ với bảng Order (Các đơn hàng sử dụng coupon này)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
