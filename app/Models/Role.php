<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{
    use HasFactory, SoftDeletes;


    // Các cột có thể điền vào
    protected $fillable = ['name'];


    // Đánh dấu soft delete
    protected $dates = ['deleted_at'];


    /**
     * Quan hệ nhiều-nhiều với bảng users thông qua bảng trung gian user_roles.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id', 'id', 'id');
    }
}


