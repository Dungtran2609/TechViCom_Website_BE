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
        'final_total', // 👈 Thêm dòng này
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'shipped_at',
        'shipping_method_id',
        'coupon_id',
        'total_weight', // thêm đây
        // mới thêm
        'province_id',
        'district_id',
        'ward_id',
        'shipping_fee',
          
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
     * Mối quan hệ với bảng UserAddress (Địa chỉ giao hàng)
     */
    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    /**
     * Mối quan hệ với các sản phẩm trong đơn hàng
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    
    
    

    /**
     * Mối quan hệ với phương thức vận chuyển
     */
    // app/Models/Order.php
    public function getShippingFeeAttribute()
    {
        return $this->final_total >= 3000000 ? 0 : 60000;
    }



    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    /**
     * Mối quan hệ với coupon
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * Mối quan hệ với transaction (thanh toán)
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Mối quan hệ với các lần trả hàng
     */
    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    /**
     * Mối quan hệ địa lý: province
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Mối quan hệ địa lý: district
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Mối quan hệ địa lý: ward
     */
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    /**
     * Dịch phương thức thanh toán sang tiếng Việt
     */
    public function getPaymentMethodVietnameseAttribute()
    {
        $paymentMethods = [
            'credit_card'   => 'Thẻ tín dụng/ghi nợ',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cod'           => 'Thanh toán khi nhận hàng',
        ];

        return $paymentMethods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Dịch trạng thái sang tiếng Việt
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
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}