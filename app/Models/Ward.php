<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = ['district_id', 'name'];

    /**
     * Một phường/xã thuộc về một quận/huyện
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Một phường/xã có nhiều đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
