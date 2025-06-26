<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description',
    ];

    /**
     * Mối quan hệ nhiều-nhiều giữa quyền và vai trò
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permissions_role');
    }
}
