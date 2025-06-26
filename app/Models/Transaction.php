<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_code',
        'amount',
        'status',
        'transaction_type',
        'paid_at',
    ];

    /**
     * Mối quan hệ với bảng Order (Đơn hàng liên quan đến giao dịch này)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Hàm kiểm tra trạng thái giao dịch có thành công hay không
     */
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    /**
     * Hàm kiểm tra giao dịch là loại 'credit' hay 'debit'
     */
    public function isCredit()
    {
        return $this->transaction_type === 'credit';
    }

    public function isDebit()
    {
        return $this->transaction_type === 'debit';
    }
}
