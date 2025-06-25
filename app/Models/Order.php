<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'address_id',
        'payment_method',
        'status',
        'total_amount',
        'created_at',
        'updated_at',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng đã tạo đơn hàng)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với bảng UserAddress (Địa chỉ giao hàng của đơn hàng)
     */
    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    /**
     * Mối quan hệ với bảng OrderItem (Các sản phẩm trong đơn hàng)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Mối quan hệ với bảng Transaction (Thông tin giao dịch liên quan đến đơn hàng)
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
