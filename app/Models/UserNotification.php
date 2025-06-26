<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'notification_id',
        'is_read',
    ];

    /**
     * Mối quan hệ với bảng User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với bảng Notification
     */
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
