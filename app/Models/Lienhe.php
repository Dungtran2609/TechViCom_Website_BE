<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lienhe extends Model
{
    protected $table = 'contacts'; // nếu bảng tên là 'contacts'

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message'];
}
