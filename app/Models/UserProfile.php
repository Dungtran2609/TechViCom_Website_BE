<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $primaryKey = 'profile_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'phone', 'province', 'district', 'ward',
        'street', 'birthday', 'gender'
    ];
}
