<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index()
    {
         $categories = NewsCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.news.news_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.news.news_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:news_categories,name',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        NewsCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.news-categories.index')->with('success', 'Thêm danh mục thành công.');
    }

    public function edit(NewsCategory $newsCategory)
    {
        return view('admin.news.news_categories.edit', compact('newsCategory'));
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:news_categories,name,' . $newsCategory->category_id . ',category_id',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        $newsCategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.news-categories.index')->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        $newsCategory->delete();

        return redirect()->route('admin.news-categories.index')->with('success', 'Xóa danh mục thành công.');
    }
}
