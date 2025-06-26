<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán giá trị đại trà
     *
     * @var array
     */
    protected $fillable = [
        'image_url',
        'link_url',
        'position',
        'is_active',
    ];

    /**
     * Hàm kiểm tra banner có đang hoạt động hay không
     */
    public function isActive()
    {
        return $this->is_active;
    }
}
