<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
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

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created successfully');
    }

    public function show(Brand $brand)
    {
        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
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

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete(); // Soft delete
        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand moved to trash');
    }

    // 👉 Danh sách soft-deleted
    public function trashed()
    {
        $brands = Brand::onlyTrashed()->get();
        return view('admin.brands.trashed', compact('brands'));
    }

    // 👉 Khôi phục
    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->find($id);

        if (!$brand) {
            return redirect()->route('admin.brands.trashed')->with('error', 'Brand not found');
        }

        $brand->restore();

        return redirect()->route('admin.brands.trashed')->with('success', 'Brand restored successfully');
    }

    // 👉 Xoá vĩnh viễn
    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->find($id);

        if (!$brand) {
            return redirect()->route('admin.brands.trashed')->with('error', 'Brand not found');
        }

        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->forceDelete();

        return redirect()->route('admin.brands.trashed')->with('success', 'Brand permanently deleted');
    }
}
