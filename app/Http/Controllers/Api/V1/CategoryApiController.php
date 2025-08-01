<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)
            ->with('parent')
            ->get();
            
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::with('parent')->findOrFail($id);
        return response()->json($category);
    }
}