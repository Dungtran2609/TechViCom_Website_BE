<?php

namespace App\Http\Controllers\Admin\Products;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductAllImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with(['brand', 'category']);

        if (request()->has('search')) {
            $search = request('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->orderByDesc('id')->paginate(5)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        // Sinh slug gốc
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $i = 1;

        // Kiểm tra slug đã tồn tại chưa, nếu có thì thêm hậu tố -1, -2, ...
        while (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('products', 'public')
            : null;

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug, // dùng slug đã kiểm tra duy nhất
            'sku' => $request->sku,
            'type' => $request->type,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'low_stock_amount' => $request->low_stock_amount,
            'thumbnail' => $thumbnailPath,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'status' => $request->status,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
        ]);

        // Xử lý ảnh phụ nếu có
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('products/gallery', 'public');
                $product->allImages()->create([
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['allImages']);
        $brands = Brand::all();
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        // 1. Cập nhật ảnh đại diện
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $product->thumbnail = $request->file('thumbnail')->store('products', 'public');
        }

        // 2. Cập nhật thông tin sản phẩm
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sku' => $request->sku,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'low_stock_amount' => $request->low_stock_amount,
            'thumbnail' => $product->thumbnail,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'status' => $request->status,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
        ]);

        // 3. Xử lý xoá ảnh phụ
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $id) {
                $image = $product->allImages()->find($id);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // 4. Cập nhật ảnh phụ cũ nếu có file mới
        if ($request->hasFile('existing_images')) {
            foreach ($request->file('existing_images') as $id => $file) {
                $image = $product->allImages()->find($id);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $path = $file->store('products/gallery', 'public');
                    $image->update(['image_path' => $path]);
                }
            }
        }

        // 5. Thêm ảnh phụ mới
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->allImages()->create([
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được ẩn.');
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->with(['brand', 'category'])->get();
        return view('admin.products.trashed', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if (!$product) {
            return redirect()->route('admin.products.trashed')->with('error', 'Không tìm thấy sản phẩm.');
        }

        $product->restore();
        return redirect()->route('admin.products.trashed')->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if (!$product) {
            return redirect()->route('admin.products.trashed')->with('error', 'Không tìm thấy sản phẩm.');
        }

        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->forceDelete();
        return redirect()->route('admin.products.trashed')->with('success', 'Đã xoá vĩnh viễn sản phẩm.');
    }
}
