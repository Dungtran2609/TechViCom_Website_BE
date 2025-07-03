<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;


    // Các cột có thể điền vào
    protected $fillable = [
        'name', 'email', 'password', 'phone_number',
        'image_profile', 'is_active', 'birthday', 'gender',
    ];


    // Cột soft delete
    protected $dates = ['deleted_at'];


    /**
     * Quan hệ nhiều-nhiều với bảng roles thông qua bảng trung gian user_roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
public function isAdmin()
{
    return $this->roles()->where('name', 'admin')->exists();
}


    /**
     * Quan hệ một-nhiều với bảng user_addresses.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }
}
