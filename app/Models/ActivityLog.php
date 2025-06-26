<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'action_type',
        'description',
        'target_table',
        'target_id',
    ];

    /**
     * Mối quan hệ với bảng User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
