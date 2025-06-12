<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|unique:product_variants,sku,' . $this->variant,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'attributes' => 'required|array',
            'attributes.*.attribute_id' => 'required|exists:attributes,attribute_id',
            'attributes.*.value_id' => 'required|exists:attribute_values,value_id',
        ];
    }
}

