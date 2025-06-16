<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Nếu đang update: $this->route('attribute') là model => cần lấy id
        $id = optional($this->route('attribute'))->id;

        return [
            'name' => 'required|string|max:100|unique:attributes,name,' . $id,
            'type' => 'required|in:text,select,color,number',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên thuộc tính.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
            'name.max' => 'Tên không được vượt quá 100 ký tự.',
            'type.required' => 'Vui lòng chọn loại thuộc tính.',
            'type.in' => 'Loại thuộc tính không hợp lệ.',
        ];
    }
}
