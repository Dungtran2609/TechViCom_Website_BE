<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có được phép thực hiện request này không.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Lấy các quy tắc validation áp dụng cho request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // Trường 'email' và 'password' là bắt buộc.
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
