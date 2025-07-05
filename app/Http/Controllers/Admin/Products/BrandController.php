<?php

namespace App\Http\Controllers\Admin\Products;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


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
    // public function index(Request $request)
    // {
        // $query = Brand::query();

        // if ($request->has('search')) {
            // $search = $request->search;
            // $query->where('name', 'like', '%' . $search . '%');
        // }

        // $query->orderBy('id', 'desc');

        // $brands = $query->paginate(5)->withQueryString();

        // return view('admin.products.brands.index', compact('brands'));

    // }

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
    // public function forceDelete($id)
    // {
        // $brand   = Brand::withTrashed()->findOrFail($id);
        // $brandId = $brand->brand_id;
        // if ($brand->image) {
            // Storage::disk('public')->delete($brand->image); // Xóa hình ảnh khi xóa vĩnh viễn
        // }
        // $brand->forceDelete();
        // return redirect()->route('admin.products.brands.trashed')->with('success', "Thương hiệu với ID $brandId đã được xóa vĩnh viễn.");
    // }

        // return view('admin.products.brands.create');
    // }

    // public function store(Request $request)
    // {
        // $request->validate([
            // 'name' => 'required|max:255',
            // 'image' => 'nullable|image|max:2048',
            // 'description' => 'nullable',
            // 'status' => 'required|boolean'
        // ]);

        // $imagePath = $request->hasFile('image')
            // ? $request->file('image')->store('brands', 'public')
            // : null;

        // Brand::create([
            // 'name' => $request->name,
            // 'slug' => Str::slug($request->name),
            // 'image' => $imagePath,
            // 'description' => $request->description,
            // 'status' => $request->status,
        // ]);

        // return redirect()->route('admin.products.brands.index')
            // ->with('success', 'Thương hiệu đã được tạo thành công.');
    // }

    // public function show(Brand $brand)
    // {
        // return view('admin.products.brands.show', compact('brand'));
    // }

    // public function edit(Brand $brand)
    // {
        // return view('admin.products.brands.edit', compact('brand'));
    // }

    // public function update(Request $request, Brand $brand)
    // {
        // $request->validate([
            // 'name' => 'required|max:255',
            // 'image' => 'nullable|image|max:2048',
            // 'description' => 'nullable',
            // 'status' => 'required|boolean'
        // ]);

        // if ($request->hasFile('image')) {
            // if ($brand->image) {
                // Storage::disk('public')->delete($brand->image);
            // }
            // $brand->image = $request->file('image')->store('brands', 'public');
        // }

        // $brand->update([
            // 'name' => $request->name,
            // 'slug' => Str::slug($request->name),
            // 'image' => $brand->image,
            // 'description' => $request->description,
            // 'status' => $request->status,
        // ]);

        // return redirect()->route('admin.products.brands.index')
            // ->with('success', 'Thương hiệu đã được cập nhật.');
    // }

    // public function destroy(Brand $brand)
    // {
        // $brand->delete(); // Soft delete
        // return redirect()->route('admin.products.brands.index')
            // ->with('success', 'Thương hiệu đã được ẩn (soft delete).');
    // }

    // public function trashed()
    // {
        // $brands = Brand::onlyTrashed()->get();
        // return view('admin.products.brands.trashed', compact('brands'));
    // }

    // public function restore($id)
    // {
        // $brand = Brand::onlyTrashed()->find($id);

        // if (!$brand) {
            // return redirect()->route('admin.products.brands.trashed')->with('error', 'Không tìm thấy thương hiệu.');
        // }

        // $brand->restore();

        // return redirect()->route('admin.products.brands.trashed')->with('success', 'Khôi phục thương hiệu thành công.');
    // }

    // public function forceDelete($id)
    // {
        // $brand = Brand::onlyTrashed()->find($id);

        // if (!$brand) {
            // return redirect()->route('admin.products.brands.trashed')->with('error', 'Không tìm thấy thương hiệu.');
        // }

        // if ($brand->image) {
            // Storage::disk('public')->delete($brand->image);
        // }

        // $brand->forceDelete();

        // return redirect()->route('admin.products.brands.trashed')->with('success', 'Đã xoá vĩnh viễn thương hiệu.');
    // }

}
