<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsApiController extends Controller
{
    // Lấy danh sách bài viết
    public function index(Request $request)
    {
        $query = News::with(['category', 'author']);

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $news = $query->orderBy('published_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    // Lấy chi tiết bài viết
    public function show($id)
    {
        $news = News::with(['category', 'author'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }
}