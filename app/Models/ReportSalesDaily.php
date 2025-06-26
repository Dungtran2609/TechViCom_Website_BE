<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSalesDaily extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'report_date',
        'total_orders',
        'total_revenue',
        'total_refunds',
        'created_at',
    ];

    /**
     * Các quy tắc xác thực cho các trường của model này (nếu có)
     *
     * @var array
     */
    public static $rules = [
        'report_date' => 'required|date',
        'total_orders' => 'required|integer',
        'total_revenue' => 'required|decimal:0,2',
        'total_refunds' => 'required|decimal:0,2',
    ];
}
