<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use App\Models\Review;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    // Hiển thị danh sách bình luận
    public function index(Request $request)
    {
        $query = NewsComment::with(['user', 'news']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('content', 'like', '%' . $keyword . '%')
                    ->orWhereHas('user', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('news', function ($q3) use ($keyword) {
                        $q3->where('title', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $comments = $query->latest()->paginate(10);

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
