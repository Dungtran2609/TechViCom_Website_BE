<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Brand extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'name', 'image', 'slug', 'description', 'status'
    ];


}



