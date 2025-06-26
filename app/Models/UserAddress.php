<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'address_line',
        'ward',
        'district',
        'city',
        'is_default',
    ];

    /**
     * Mối quan hệ với bảng User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
