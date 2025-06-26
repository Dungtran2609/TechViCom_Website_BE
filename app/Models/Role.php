<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Mối quan hệ nhiều-nhiều giữa vai trò và người dùng
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * Mối quan hệ nhiều-nhiều giữa vai trò và quyền
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permissions_role');
    }

    /**
     * Kiểm tra xem vai trò này có quyền nào hay không
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}
