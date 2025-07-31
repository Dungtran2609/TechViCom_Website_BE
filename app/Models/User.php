<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

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
     * Mối quan hệ người dùng và các địa chỉ
     */
    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    /**
     * Mối quan hệ người dùng và các đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
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
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Kiểm tra xem người dùng có quyền cụ thể không (thông qua các vai trò).
     */
    public function hasPermission(string $permissionName): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }

        return false;
    }
}
