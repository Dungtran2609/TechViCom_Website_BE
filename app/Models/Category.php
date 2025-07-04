<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Nếu bảng không phải là 'categories', hãy khai báo:
    // protected $table = 'categories';

    // Nếu không dùng khóa chính mặc định 'id', hãy khai báo:
    // protected $primaryKey = 'id';

    // Các trường có thể gán giá trị hàng loạt
    protected $fillable = [
        'name',
        'description',
        // Thêm các trường khác nếu có
    ];
}
