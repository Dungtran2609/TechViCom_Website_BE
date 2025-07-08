<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

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
        'final_total',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'shipped_at',
        'shipping_method_id', // Khóa ngoại cho shipping_method
        'coupon_id',         // Khóa ngoại cho coupon
    ];

    /**
     * Các trường kiểu ngày (Carbon instance)
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'shipped_at',
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
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Mối quan hệ với bảng ShippingMethod (Phương thức vận chuyển)
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class,'shipping_method_id');
    }

    /**
     * Mối quan hệ với bảng Coupon (Mã giảm giá)
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class,'coupon_id');
    }

    /**
     * Mối quan hệ với bảng Transaction (Thông tin giao dịch liên quan đến đơn hàng)
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Accessor để dịch phương thức thanh toán sang tiếng Việt
     *
     * @return string
     */
    public function getPaymentMethodVietnameseAttribute()
    {
        $paymentMethods = [
            'credit_card'   => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod'           => 'Thanh toán khi nhận hàng',

            // COD: Cash on Delivery
            // Thêm các phương thức khác nếu cần
        ];

        return $paymentMethods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Accessor để dịch trạng thái sang tiếng Việt
     *
     * @return string
     */
    public function getStatusVietnameseAttribute()
    {
        $statuses = [
            'pending'    => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped'    => 'Đã giao',
            'delivered'  => 'Đã nhận',
            'cancelled'  => 'Đã hủy',
            'returned'   => 'Đã trả hàng',
            // Thêm các trạng thái khác nếu cần
        ];

        return $statuses[$this->status] ?? $this->status;
    }
    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }
}
