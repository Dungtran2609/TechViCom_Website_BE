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
    /**
 * Mối quan hệ với bảng Category (Danh mục cha)
 */
public function parent()
{
        return $this->belongsTo(Category::class, 'parent_id');
}
/**
 * Mối quan hệ với các bảng Product (Sản phẩm thuộc danh mục này)
 */
public function products()
{
    return $this->hasMany(Product::class);
}
}
