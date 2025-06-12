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
        return [
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'brand_id' => 'required|exists:brands,brand_id',
            'category_id' => 'required|exists:categories,category_id',
            'status' => 'required|boolean',
            'product_images.*' => 'nullable|image|max:2048',
        ];
    }
}

