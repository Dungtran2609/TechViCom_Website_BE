<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'content', 'rating', 'status', 'parent_id'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DELETED = 'deleted';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replies()
    {
        return $this->hasMany(ProductComment::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(ProductComment::class, 'parent_id');
    }
}
