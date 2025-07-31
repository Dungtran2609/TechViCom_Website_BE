<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reason',
        'type',
        'status',
        'requested_at',
        'processed_at',
        'admin_note',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Quan hệ với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
