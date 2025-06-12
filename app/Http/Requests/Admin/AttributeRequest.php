<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép tất cả người dùng (bạn có thể thay đổi logic phân quyền nếu cần)
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'values' => ['required', 'array', 'min:1'],
            'values.*' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
            'values.required' => 'Bạn phải nhập ít nhất một giá trị cho thuộc tính.',
            'values.*.required' => 'Giá trị không được để trống.',
        ];
    }
}
