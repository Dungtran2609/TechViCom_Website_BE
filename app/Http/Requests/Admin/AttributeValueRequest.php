<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required|string|max:100',
            'color_code' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'Vui lòng nhập giá trị thuộc tính.',
            'value.max' => 'Giá trị không được vượt quá 100 ký tự.',
            'color_code.max' => 'Mã màu không được vượt quá 20 ký tự.',
        ];
    }
}
