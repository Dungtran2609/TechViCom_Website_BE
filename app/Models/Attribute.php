<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán đại trà
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Mối quan hệ với bảng AttributeValue (Các giá trị của thuộc tính này)
     */
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
