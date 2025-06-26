<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportUserActivity extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'activity_date',
        'login_count',
        'order_count',
        'comment_count',
        'page_views',
        'time_spent',
        'interactions',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng User (Người dùng tham gia hoạt động)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Các quy tắc xác thực cho các trường của model này (nếu có)
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'activity_date' => 'required|date',
        'login_count' => 'required|integer',
        'order_count' => 'required|integer',
        'comment_count' => 'required|integer',
        'page_views' => 'required|integer',
        'time_spent' => 'required|integer',
        'interactions' => 'required|integer',
    ];
}
