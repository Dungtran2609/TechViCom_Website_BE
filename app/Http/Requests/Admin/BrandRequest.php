<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
            'image' => 'nullable|image|max:2048',
            'slug' => 'nullable|max:100',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ];
    }
}
