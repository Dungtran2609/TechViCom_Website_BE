<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        // Bạn có thể lấy danh sách category ở đây nếu cần
        $categories = Category::all();
        return view('admin.products.categories.index', compact('categories'));

        
    }
}
