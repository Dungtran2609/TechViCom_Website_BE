<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * Mối quan hệ với bảng Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mối quan hệ với bảng Permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
