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

    /**
     * Các cột có thể điền vào.
     */
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

    /**
     * Cột soft delete.
     */
    protected $dates = ['deleted_at'];

    /**
     * Quan hệ nhiều-nhiều với bảng roles thông qua bảng trung gian user_roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,          // Model Role
            'user_roles',         // Bảng trung gian
            'user_id',            // Khóa ngoại trong bảng trung gian liên kết với User
            'role_id',            // Khóa ngoại trong bảng trung gian liên kết với Role
            'id',                 // Khóa chính trong bảng User
            'id'                  // Khóa chính trong bảng Role
        );
    }

    /**
     * Quan hệ một-nhiều với bảng user_addresses.
     */
    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }
    public function addresses(): HasMany
    {
        return $this->hasMany(
            UserAddress::class,  // Model UserAddress
            'user_id',           // Khóa ngoại trong bảng UserAddress
            'id'                 // Khóa chính trong bảng User
        );
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
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('name', $roles)->exists();
        }
        return $this->roles()->where('name', $roles)->exists();
    }

    public function productComments()
    {
        return $this->hasMany(ProductComment::class);
    }
}
