<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $imagePath = $request->file('image')
            ? $request->file('image')->store('categories', 'public')
            : null;

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('category_id', '!=', $category->category_id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'image' => $category->image,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Category $category)
    {
        $category->delete(); // Soft delete
        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được ẩn (soft delete).');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children']);
        return view('admin.categories.show', compact('category'));
    }

    // Hiển thị danh mục đã bị xoá mềm (thùng rác)
public function trashed()
{
    $categories = Category::onlyTrashed()->with('parent')->get();
    return view('admin.categories.trashed', compact('categories'));
}

// Khôi phục danh mục đã bị xoá mềm
public function restore($id)
{
    $category = Category::onlyTrashed()->find($id);
    if ($category) {
        $category->restore();
    }
    return redirect()->route('admin.categories.trashed')->with('success', 'Khôi phục thành công.');
}

// Xoá vĩnh viễn
public function forceDelete($id)
{
    $category = Category::onlyTrashed()->find($id);
    if ($category) {
        // Xoá ảnh nếu có
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->forceDelete();
    }
    return redirect()->route('admin.categories.trashed')->with('success', 'Đã xoá vĩnh viễn.');
}

}
