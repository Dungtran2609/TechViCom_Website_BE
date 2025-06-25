<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'status',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng Order (Đơn hàng liên quan đến yêu cầu hoàn tiền)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mối quan hệ với bảng User (Người dùng tạo yêu cầu hoàn tiền)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hàm kiểm tra trạng thái yêu cầu hoàn tiền
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
