<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with(['brand', 'category']);

        if (request()->has('search')) {
            $search = request('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    private function generateUniqueSku(string $name): string
    {
        $baseSku = strtoupper(substr(Str::slug($name), 0, 6));
        do {
            $randomPart = Str::upper(Str::random(6));
            $sku = $baseSku . '-' . $randomPart;
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    public function store(ProductRequest $request)
    {
        $dataToCreate = $request->validated();

        $dataToCreate['slug'] = Str::slug($dataToCreate['name']);
        while (Product::withTrashed()->where('slug', $dataToCreate['slug'])->exists()) {
            $dataToCreate['slug'] = Str::slug($dataToCreate['name']) . '-' . Str::lower(Str::random(4));
        }

        if ($request->hasFile('thumbnail')) {
            $dataToCreate['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $dataToCreate['is_featured'] = $request->has('is_featured');

        if ($dataToCreate['type'] === 'simple') {
            if (empty($dataToCreate['sku'])) {
                $dataToCreate['sku'] = $this->generateUniqueSku($dataToCreate['name']);
            }
        } else { // type === 'variable'
            $dataToCreate['price'] = null;
            $dataToCreate['stock'] = null;
            $dataToCreate['sku'] = null;
            $dataToCreate['sale_price'] = null;
            $dataToCreate['low_stock_amount'] = null;
        }

        $product = Product::create($dataToCreate);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('products/gallery', 'public');
                $product->allImages()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'allImages']);
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
        $dataToUpdate = $request->validated();

        if ($request->name !== $product->name) {
            $dataToUpdate['slug'] = Str::slug($request->name);
            while (Product::withTrashed()->where('slug', $dataToUpdate['slug'])->where('id', '!=', $product->id)->exists()) {
                $dataToUpdate['slug'] = Str::slug($request->name) . '-' . Str::lower(Str::random(4));
            }
        }

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $dataToUpdate['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $dataToUpdate['is_featured'] = $request->has('is_featured');

        if ($dataToUpdate['type'] === 'simple') {
            if (empty($dataToUpdate['sku'])) {
                $dataToUpdate['sku'] = $this->generateUniqueSku($dataToUpdate['name']);
            }
        } else { // type === 'variable'
            $dataToUpdate['price'] = null;
            $dataToUpdate['stock'] = null;
            $dataToUpdate['sku'] = null;
            $dataToUpdate['sale_price'] = null;
            $dataToUpdate['low_stock_amount'] = null;
        }

        $product->update($dataToUpdate);

        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $id) {
                $image = $product->allImages()->find($id);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->allImages()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được chuyển vào thùng rác.');
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->with(['brand', 'category'])->orderByDesc('deleted_at')->paginate(10);
        return view('admin.products.trashed', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('admin.products.trashed')->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        
        foreach ($product->allImages as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->forceDelete();
        return redirect()->route('admin.products.trashed')->with('success', 'Đã xoá vĩnh viễn sản phẩm.');
    }
}
