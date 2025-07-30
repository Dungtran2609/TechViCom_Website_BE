<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::latest()->take(8)->get();

        return view('client.home', compact('categories', 'products'));
    }

    // API Methods for React Frontend
    public function apiCategories(): JsonResponse
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('status', 'active');
        }])->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function apiCategoryProducts($categoryId): JsonResponse
    {
        $category = Category::with(['products' => function($query) {
            $query->where('status', 'active')
                  ->with(['brand', 'variants']);
        }])->findOrFail($categoryId);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function apiBanners(): JsonResponse
    {
        // Mock banner data - bạn có thể tạo model Banner sau
        $banners = [
            [
                'id' => 1,
                'title' => 'Khuyến mãi mùa hè',
                'description' => 'Giảm giá lên đến 50% cho các sản phẩm điện tử',
                'image' => '/images/banners/banner1.jpg',
                'link' => '/products?category=electronics'
            ],
            [
                'id' => 2,
                'title' => 'Laptop Gaming',
                'description' => 'Bộ sưu tập laptop gaming chất lượng cao',
                'image' => '/images/banners/banner2.jpg',
                'link' => '/products?category=gaming'
            ],
            [
                'id' => 3,
                'title' => 'Điện thoại mới',
                'description' => 'Cập nhật những mẫu điện thoại mới nhất',
                'image' => '/images/banners/banner3.jpg',
                'link' => '/products?category=phones'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }

    public function apiFeaturedProducts(): JsonResponse
    {
        $products = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function apiNews(): JsonResponse
    {
        $news = News::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }
}