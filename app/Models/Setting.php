<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'config_key',
        'config_value',
        'updated_at',
    ];

    /**
     * Các quy tắc xác thực cho các trường của model này (nếu có)
     *
     * @var array
     */
    public static $rules = [
        'config_key' => 'required|string|max:100',
        'config_value' => 'required|string|max:255',
    ];
}
