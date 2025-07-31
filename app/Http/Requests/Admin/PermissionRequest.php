<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Bạn có thể tùy chỉnh quyền ở đây (true để cho phép mọi request)
        return true;
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')?->id ?? null;

        return [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permissionId,
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên quyền là bắt buộc.',
            'name.unique' => 'Tên quyền đã tồn tại.',
            'name.max' => 'Tên quyền không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        ];
    }
}
