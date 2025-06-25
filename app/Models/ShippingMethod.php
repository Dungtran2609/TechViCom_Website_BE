<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'fee',
        'estimated_days',
        'max_weight',
        'regions',
    ];

    /**
     * Mối quan hệ với bảng Order (Các đơn hàng sử dụng phương thức vận chuyển này)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Hàm kiểm tra xem phương thức vận chuyển có áp dụng cho khu vực hay không
     *
     * @param string $region
     * @return bool
     */
    public function supportsRegion($region)
    {
        $regions = explode(',', $this->regions);
        return in_array($region, $regions);
    }
}
