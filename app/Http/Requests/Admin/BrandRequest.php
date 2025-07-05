<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Có thể thay bằng logic kiểm tra quyền (ví dụ: auth()->check())
    }

    public function rules(): array
    {
        return [
            'name'        => [
                'required',
                'string',
                'max:100',
                Rule::unique('brands', 'name')->ignore($this->route('brand')), // Bỏ qua bản ghi hiện tại nếu là update
            ],
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048', // Hình ảnh tùy chọn, tối đa 2MB
            'status'      => 'required|boolean',        // Trạng thái bắt buộc, phải là boolean
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Tên thương hiệu là bắt buộc.',
            'name.string'        => 'Tên thương hiệu phải là chuỗi ký tự.',
            'name.max'           => 'Tên thương hiệu không được vượt quá 100 ký tự.',
            'name.unique'        => 'Tên thương hiệu đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'image.image'        => 'Tệp phải là hình ảnh.',
            'image.max'          => 'Hình ảnh không được vượt quá 2MB.',
            'status.required'    => 'Trạng thái là bắt buộc.',
            'status.boolean'     => 'Trạng thái phải là đúng hoặc sai.',
        ];
    }
}
