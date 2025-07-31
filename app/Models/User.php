<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'image_profile',
        'is_active',
        'birthday',
        'gender',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Quan hệ nhiều-nhiều với bảng roles thông qua bảng user_roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }


    /**
     * Quan hệ một-nhiều với bảng user_addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    /**
     * Kiểm tra xem người dùng có vai trò cụ thể không.
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('name', $roles)->exists(); // ✅ dùng name
        }

        return $this->roles()->where('name', $roles)->exists(); // ✅ dùng name
    }

    /**
     * Kiểm tra xem người dùng có phải admin không.
     */
    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }


    /**
     * Kiểm tra xem người dùng có quyền cụ thể không (thông qua các vai trò).
     */
    public function hasPermission($permissionName)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }
}
