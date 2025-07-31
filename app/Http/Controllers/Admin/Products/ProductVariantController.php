<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Storage;

use App\Models\ProductVariantAttribute;

class ProductVariantController extends Controller
{
    // Danh sách biến thể của sản phẩm
    public function index(Product $product)
    {
        $variants = $product->variants()->with('attributesValue.attribute')->get();

        return view('admin.products.variants.index', compact('product', 'variants'));
    }
    public function show(ProductVariant $variant)
    {
        $product = $variant->product;
        $attributes = $variant->attributesValue()->with('attribute')->get();

        return view('admin.products.variants.show', compact('variant', 'product', 'attributes'));
    }

    // Hiển thị form thêm biến thể
    public function create(Product $product)
    {
        $attributes = Attribute::with('values')->get();

        return view('admin.products.variants.create', compact('product', 'attributes'));
    }
    public function selectProduct()
    {
        $products = Product::orderBy('name')->get();

        return view('admin.products.variants.select', compact('products'));
    }

    // Lưu biến thể mới
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'attribute_values' => 'required|array|min:1',
        ]);

        // Upload ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variants', 'public');
        }

        // Tạo biến thể
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => strtoupper('SKU-' . uniqid()),
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'weight' => $request->weight ?? 0,
            'dimensions' => $request->dimensions ?? '',
            'image' => $imagePath,
        ]);


        // Gắn thuộc tính
        foreach ($request->attribute_values as $valueId) {
            ProductVariantAttribute::create([
                'product_variant_id' => $variant->id,
                'attribute_value_id' => $valueId,
            ]);
        }

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Thêm biến thể thành công.');
    }

    // Hiển thị form sửa biến thể
    public function edit(ProductVariant $variant)
    {
        $product = $variant->product;
        $attributes = Attribute::with('values')->get();
        $selectedValues = $variant->attributesValue->pluck('id')->toArray();

        return view('admin.products.variants.edit', compact('variant', 'product', 'attributes', 'selectedValues'));
    }

    // Cập nhật biến thể
    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'attribute_values' => 'required|array|min:1',
        ]);

        $data = [
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'weight' => $request->weight ?? 0,
            'dimensions' => $request->dimensions ?? '',
        ];

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $data['image'] = $request->file('image')->store('variants', 'public');
        }

        $variant->update($data);

        // Cập nhật các giá trị thuộc tính
        ProductVariantAttribute::where('product_variant_id', $variant->id)->delete();
        foreach ($request->attribute_values as $valueId) {
            ProductVariantAttribute::create([
                'product_variant_id' => $variant->id,
                'attribute_value_id' => $valueId,
            ]);
        }

        return redirect()->route('admin.products.variants.index', $variant->product_id)
            ->with('success', 'Cập nhật biến thể thành công.');
    }

    // Xoá biến thể
    public function destroy(ProductVariant $variant)
    {
        $productId = $variant->product_id;

        // Xoá liên kết
        ProductVariantAttribute::where('product_variant_id', $variant->id)->delete();

        $variant->delete();

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Xóa biến thể thành công.');
    }
}
