<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'name'];

    /**
     * Một quận/huyện thuộc về một tỉnh
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Một quận/huyện có nhiều phường/xã
     */
    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    /**
     * Một quận/huyện có nhiều đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
