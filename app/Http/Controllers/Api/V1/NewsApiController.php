<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class NewsApiController extends Controller
{
    /**
     * Lấy danh sách bài viết
     */
    public function index(Request $request): JsonResponse
    {
        $query = News::with(['author', 'category']);

        // Lọc theo trạng thái nổi bật
        if ($request->boolean('featured')) {
            $query->where('status', 'featured');
        }

        // Tìm kiếm theo tiêu đề
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Lọc theo category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'published_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $news = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'pagination' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ]
        ]);
    }

    /**
     * Lấy chi tiết bài viết
     */
    public function show(int $id): JsonResponse
    {
        $news = News::with(['author', 'category'])
            ->findOrFail($id);

        // Lấy comments chỉ những comment được phép hiển thị
        $comments = $news->comments()
            ->with('user')
            ->where('is_hidden', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $news->setRelation('comments', $comments);

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Tạo bài viết mới
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string|url',
            'author_id' => 'required|integer|exists:users,id',
            'category_id' => 'required|integer|exists:news_categories,category_id',
            'status' => 'nullable|string|in:draft,published,featured',
            'published_at' => 'nullable|date',
        ]);

        $news = News::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được tạo thành công',
            'data' => $news->load(['author', 'category'])
        ], 201);
    }

    /**
     * Cập nhật bài viết
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'nullable|string|url',
            'author_id' => 'sometimes|integer|exists:users,id',
            'category_id' => 'sometimes|integer|exists:news_categories,category_id',
            'status' => 'nullable|string|in:draft,published,featured',
            'published_at' => 'nullable|date',
        ]);

        $news->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được cập nhật thành công',
            'data' => $news->load(['author', 'category'])
        ]);
    }

    /**
     * Xóa bài viết
     */
    public function destroy(int $id): JsonResponse
    {
        $news = News::findOrFail($id);
        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được xóa thành công'
        ]);
    }

    /**
     * Lấy comments của bài viết
     */
    public function comments(int $newsId): JsonResponse
    {
        $news = News::findOrFail($newsId);
        $comments = $news->comments()
            ->with('user')
            ->where('is_hidden', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Thêm comment vào bài viết
     */
    public function addComment(Request $request, int $newsId): JsonResponse
    {
        $news = News::findOrFail($newsId);

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $validated['news_id'] = $newsId;
        $validated['is_hidden'] = false; // Đảm bảo comment mới luôn hiển thị
        $comment = NewsComment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Comment đã được thêm thành công',
            'data' => $comment->load('user')
        ], 201);
    }

    /**
     * Lấy bài viết nổi bật
     */
    public function featured(): JsonResponse
    {
        $featuredNews = News::with(['author', 'category'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $featuredNews
        ]);
    }

    /**
     * Lấy danh sách danh mục tin tức
     */
    public function categories(): JsonResponse
    {
        $categories = \App\Models\NewsCategory::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Lấy bài viết theo danh mục
     */
    public function newsByCategory(int $categoryId): JsonResponse
    {
        $category = \App\Models\NewsCategory::findOrFail($categoryId);
        
        $news = News::with(['author', 'category'])
            ->where('category_id', $categoryId)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }
}