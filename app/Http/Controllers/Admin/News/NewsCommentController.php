<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use App\Models\Review;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    // Hiển thị danh sách bình luận
    public function index()
    {
        $comments = NewsComment::with(['user', 'news'])->latest()->paginate(10);
        return view('admin.news.comments', compact('comments'));
    }

    // Xóa bình luận
    public function destroy($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.news-comments.index')->with('success', 'Đã xoá bình luận thành công.');
    }

    // Ẩn hoặc hiện bình luận
    public function toggleVisibility($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->is_hidden = !$comment->is_hidden;
        $comment->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái bình luận thành công.');
    }
}
