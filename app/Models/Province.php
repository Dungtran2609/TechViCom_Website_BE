<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Một tỉnh có nhiều quận/huyện
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Một tỉnh có nhiều đơn hàng (thông qua orders)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
