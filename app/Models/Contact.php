<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts'; // nếu bảng tên là 'contacts'

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'is_read'];
}
