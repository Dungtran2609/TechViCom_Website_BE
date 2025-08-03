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

        if ($request->boolean('featured')) {
            $query->where('status', 'featured');
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $sortBy = $request->get('sort_by', 'published_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

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
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $news = \App\Models\News::findOrFail($id); // Sẽ trả về 404 nếu không tìm thấy

        $news->load(['author', 'category'])->loadCount('likes');
        $news->is_liked_by_user = $user ? $user->hasLikedNews($news) : false;

        $comments = $news->comments()
            ->with(['user', 'children' => function ($query) use ($user) {
                $query->with('user')->withCount('likes');
                if ($user) {
                    $query->withCasts(['is_liked_by_user' => 'boolean']);
                    $query->addSelect([
                        'is_liked_by_user' => \App\Models\Like::select('id')
                            ->where('user_id', $user->id)
                            ->whereColumn('likeable_id', 'news_comments.id')
                            ->where('likeable_type', \App\Models\NewsComment::class)
                            ->limit(1)
                    ]);
                }
            }])
            ->withCount('likes')
            ->where('is_hidden', false)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($user) {
            $comments->each(function ($comment) use ($user) {
                $comment->is_liked_by_user = $user->hasLikedComment($comment);
            });
        }

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
     * Thêm comment (gốc hoặc trả lời)
     */
    public function addComment(Request $request, News $news): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:news_comments,id',
        ]);

        $comment = $news->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'is_hidden' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment đã được thêm thành công',
            'data' => $comment->load('user')
        ], 201);
    }

    /**
     * Thích hoặc bỏ thích bài viết
     */
    public function toggleLikePost(Request $request, News $news): JsonResponse
    {
        $user = $request->user();
        $like = $news->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $message = 'Đã bỏ thích bài viết.';
        } else {
            $news->likes()->create(['user_id' => $user->id]);
            $message = 'Đã thích bài viết.';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Thích hoặc bỏ thích bình luận
     */
    public function toggleLikeComment(Request $request, NewsComment $comment): JsonResponse
    {
        $user = $request->user();
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $message = 'Đã bỏ thích bình luận.';
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $message = 'Đã thích bình luận.';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
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
     * Lấy danh mục tin tức
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
