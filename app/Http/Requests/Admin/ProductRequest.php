<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $productId = $this->route('product')?->id;
    $type = $this->input('type', 'simple');

    return [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId,
        'type' => 'in:simple,variable',

        'sku' => $type === 'simple' ? 'required|string|max:100|unique:products,sku,' . $productId : 'nullable|string|max:100',
        'price' => $type === 'simple' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
        'sale_price' => 'nullable|numeric|min:0|lte:price',
        'stock' => $type === 'simple' ? 'required|integer|min:0' : 'nullable|integer|min:0',
        'low_stock_amount' => 'nullable|integer|min:0',

        'thumbnail' => $this->isMethod('post')
            ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
            : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

        'short_description' => 'nullable|string|max:1000',
        'long_description' => 'nullable|string',

        'status' => 'required|in:active,inactive',
        'brand_id' => 'required|exists:brands,id',
        'category_id' => 'required|exists:categories,id',
    ];
}


    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'slug.unique' => 'Slug đã tồn tại. Vui lòng chọn slug khác.',
            'sku.required' => 'Mã SKU là bắt buộc.',
            'sku.unique' => 'Mã SKU đã tồn tại. Vui lòng chọn mã khác.',
            'sku.max' => 'Mã SKU không được dài quá 100 ký tự.',
            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá phải là số.',
            'stock.required' => 'Tồn kho không được để trống.',
            'stock.integer' => 'Tồn kho phải là số nguyên.',
            'thumbnail.required' => 'Ảnh đại diện là bắt buộc khi thêm mới.',
            'thumbnail.image' => 'File phải là hình ảnh.',
            'thumbnail.mimes' => 'Định dạng ảnh không hợp lệ (jpeg, png, jpg, webp).',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'brand_id.required' => 'Thương hiệu là bắt buộc.',
            'brand_id.exists' => 'Thương hiệu không tồn tại.',
            'status.required' => 'Trạng thái sản phẩm là bắt buộc.',
            'status.in' => 'Trạng thái sản phẩm không hợp lệ.',
            'sale_price.lte' => 'Giá khuyến mãi không được lớn hơn giá gốc.',
            'low_stock_amount.integer' => 'Số lượng cảnh báo phải là số nguyên.',
            'low_stock_amount.min' => 'Số lượng cảnh báo không được nhỏ hơn 0.',
        ];
    }
}
