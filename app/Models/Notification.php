<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'message',
        'type',
    ];

    /**
     * Mối quan hệ với bảng UserNotification
     */
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
