<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'device_info',
        'login_status',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng đã đăng nhập)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hàm kiểm tra trạng thái đăng nhập (Thành công hoặc thất bại)
     */
    public function isSuccess()
    {
        return $this->login_status === 'success';
    }
}
