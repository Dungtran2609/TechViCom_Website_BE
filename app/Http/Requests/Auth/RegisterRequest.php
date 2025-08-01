<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có được phép thực hiện request này không.
     *
     * @return bool
     */
    public function authorize()
    {
        // Trả về 'true' để cho phép tất cả mọi người có thể thực hiện request này.
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
            // Trường 'name' là bắt buộc (required), phải là chuỗi (string) và có độ dài tối đa 255 ký tự.
            'name'     => ['required', 'string', 'max:255'],

            // Trường 'email' là bắt buộc, phải là chuỗi, có định dạng email, độ dài tối đa 255 ký tự,
            // và quan trọng nhất, phải là duy nhất (unique) trong cột 'email' của bảng 'users'.
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],

            // Trường 'password' là bắt buộc, phải là chuỗi, có độ dài tối thiểu 8 ký tự,
            // và phải được xác nhận (confirmed). Quy tắc 'confirmed' yêu cầu phải có một
            // trường 'password_confirmation' có giá trị khớp.
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
