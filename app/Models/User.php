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
    public function roles(): BelongsToMany
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
     * Kiểm tra xem người dùng có vai trò cụ thể không (theo slug).
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('slug', $roles)->exists();
        }

        return $this->roles()->where('slug', $roles)->exists();
    }

    /**
     * Kiểm tra xem người dùng có phải admin không (theo slug 'admin').
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Kiểm tra xem người dùng có quyền cụ thể không (thông qua các vai trò).
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }
}
