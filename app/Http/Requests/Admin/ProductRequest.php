<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->ignore($productId),
            ],
            'type' => 'required|in:simple,variable',
            'price' => 'required_if:type,simple|nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'stock' => 'required_if:type,simple|nullable|integer|min:0',
            'low_stock_amount' => 'nullable|integer|min:0',
            'thumbnail' => $this->isMethod('post')
                ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
                : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'short_description' => 'nullable|string|max:1000',
            'long_description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
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
            'sku.unique' => 'Mã SKU bạn nhập đã tồn tại. Vui lòng chọn mã khác.',
            'sku.max' => 'Mã SKU không được dài quá 100 ký tự.',
            'type.required' => 'Vui lòng chọn loại sản phẩm.',
            'type.in' => 'Loại sản phẩm không hợp lệ.',
            'price.required_if' => 'Giá sản phẩm là bắt buộc cho sản phẩm đơn thể.',
            'price.numeric' => 'Giá phải là một con số.',
            'sale_price.lte' => 'Giá khuyến mãi không được lớn hơn giá gốc.',
            'stock.required_if' => 'Tồn kho là bắt buộc cho sản phẩm đơn thể.',
            'stock.integer' => 'Tồn kho phải là một số nguyên.',
            'low_stock_amount.integer' => 'Số lượng cảnh báo phải là số nguyên.',
            'low_stock_amount.min' => 'Số lượng cảnh báo không được nhỏ hơn 0.',
            'thumbnail.required' => 'Ảnh đại diện là bắt buộc khi thêm mới.',
            'thumbnail.image' => 'Tệp tải lên phải là hình ảnh.',
            'thumbnail.mimes' => 'Định dạng ảnh không hợp lệ (chỉ chấp nhận: jpeg, png, jpg, webp).',
            'category_id.required' => 'Vui lòng chọn danh mục cho sản phẩm.',
            'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu cho sản phẩm.',
            'brand_id.exists' => 'Thương hiệu đã chọn không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái sản phẩm.',
            'status.in' => 'Trạng thái sản phẩm không hợp lệ.',
        ];
    }
}