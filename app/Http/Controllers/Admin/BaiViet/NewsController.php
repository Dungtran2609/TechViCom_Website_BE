<?php

namespace App\Http\Controllers\Admin\BaiViet;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->orderBy('published_at', 'desc')->paginate(10);

        return view('admin.BaiViet.index', compact('news'));
    }

    public function create()
    {
        $categories = NewsCategory::all();
        return view('admin.BaiViet.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author_id' => 'nullable|exists:users,user_id',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:news_categories,category_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'content.required' => 'Nội dung là bắt buộc.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'published_at.date' => 'Ngày đăng không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function show(News $news)
    {
        return view('admin.BaiViet.show', compact('news'));
    }

    public function edit(News $news)
    {
        $categories = NewsCategory::all();
        return view('admin.BaiViet.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author_id' => 'nullable|exists:users,user_id',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:news_categories,category_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'content.required' => 'Nội dung là bắt buộc.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'published_at.date' => 'Ngày đăng không hợp lệ.',
            'category_id.exists' => 'Danh mục được chọn không tồn tại.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        // Nếu có ảnh mới được tải lên, lưu lại
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được cập nhật thành công.');
    }


    public function destroy(News $news)
    {
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được xoá.');
    }
}
