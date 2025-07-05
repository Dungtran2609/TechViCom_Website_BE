<?php

namespace App\Http\Controllers\Admin\Products;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $query = Brand::query();
        if (request()->has('search')) {
            $search = request('search');
            $query->where('name', 'like', '%' . $search . '%');
        }
        $brands = $query->paginate(5);                   // Đảm bảo phân trang
        \Log::info('Total brands: ' . $brands->total()); // Debug số lượng bản ghi
        return view('admin.products.brands.listBrands', compact('brands'));
    }

    public function create()
    {
        return view('admin.products.brands.createBrands');
    }

    public function store(BrandRequest $request)
    {
        $data = $request->only(['name', 'description', 'status']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('brands', 'public');
        }

        Brand::create([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'],
            'image'       => $data['image'] ?? null,
            'status'      => $data['status'] ?? true, // Mặc định là true nếu không chọn
        ]);

        return redirect()->route('admin.products.brands.index')->with('success', 'Thêm thương hiệu thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return view('admin.products.brands.detailBrand', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.products.brands.editBrands', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        $data = $request->only(['name', 'description', 'status']);
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu tồn tại
            if ($brand->image) {
                Storage::delete($brand->image);
            }
            $data['image'] = $request->file('image')->store('brands', 'public');
        }

        $brand->update([
            'name'        => $data['name'],

            'description' => $data['description'],
            'image'       => $data['image'] ?? $brand->image, // Giữ hình ảnh cũ nếu không thay đổi
            'status'      => $data['status'] ?? $brand->status,
            'slug'        => $brand->slug, // Giữ slug không đổi
        ]);

        return redirect()->route('admin.products.brands.index')->with('success', 'Sửa thương hiệu thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brandId = $brand->brand_id;
        $brand->delete();
        return redirect()->route('admin.products.brands.index')->with('success', "Thương hiệu với ID $brandId đã được xóa thành công.");
    }

    /**
     * Display a listing of the trashed brands.
     */
    public function trashed()
    {
        $brands = Brand::onlyTrashed()->paginate(10); // Sử dụng paginate để phân trang
        return view('admin.products.brands.trashedBrands', compact('brands'));
    }

    /**
     * Restore the specified brand.
     */
    public function restore($id)
    {
        $brand   = Brand::withTrashed()->findOrFail($id);
        $brandId = $brand->brand_id;
        $brand->restore();
        return redirect()->route('admin.products.brands.trashed')->with('success', "Thương hiệu với ID $brandId đã được khôi phục thành công.");
    }

    /**
     * Force delete the specified brand.
     */
    public function forceDelete($id)
    {
        $brand   = Brand::withTrashed()->findOrFail($id);
        $brandId = $brand->brand_id;
        if ($brand->image) {
            Storage::disk('public')->delete($brand->image); // Xóa hình ảnh khi xóa vĩnh viễn
        }
        $brand->forceDelete();
        return redirect()->route('admin.products.brands.trashed')->with('success', "Thương hiệu với ID $brandId đã được xóa vĩnh viễn.");
    }

}
