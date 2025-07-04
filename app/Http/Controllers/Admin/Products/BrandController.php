<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $query->orderBy('id', 'desc');

        $brands = $query->paginate(5)->withQueryString();

        return view('admin.products.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.products.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'status' => 'required|boolean'
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('brands', 'public')
            : null;

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.brands.index')
            ->with('success', 'Thương hiệu đã được tạo thành công.');
    }

    public function show(Brand $brand)
    {
        return view('admin.products.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.products.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'status' => 'required|boolean'
        ]);

        if ($request->hasFile('image')) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $brand->image = $request->file('image')->store('brands', 'public');
        }

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $brand->image,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.brands.index')
            ->with('success', 'Thương hiệu đã được cập nhật.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete(); // Soft delete
        return redirect()->route('admin.products.brands.index')
            ->with('success', 'Thương hiệu đã được ẩn (soft delete).');
    }

    public function trashed()
    {
        $brands = Brand::onlyTrashed()->get();
        return view('admin.products.brands.trashed', compact('brands'));
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->find($id);

        if (!$brand) {
            return redirect()->route('admin.products.brands.trashed')->with('error', 'Không tìm thấy thương hiệu.');
        }

        $brand->restore();

        return redirect()->route('admin.products.brands.trashed')->with('success', 'Khôi phục thương hiệu thành công.');
    }

    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->find($id);

        if (!$brand) {
            return redirect()->route('admin.products.brands.trashed')->with('error', 'Không tìm thấy thương hiệu.');
        }

        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->forceDelete();

        return redirect()->route('admin.products.brands.trashed')->with('success', 'Đã xoá vĩnh viễn thương hiệu.');
    }
}
