<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Xử lý request đăng ký người dùng mới.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // Khi đoạn code này được chạy, có nghĩa là dữ liệu từ request đã được validate và hợp lệ.
        // Các trường 'name', 'email', 'password' và 'password_confirmation' đều đã được kiểm tra.
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Băm (hash) mật khẩu trước khi lưu vào database
        ]);

        // Tạo một token truy cập mới cho người dùng vừa đăng ký
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message'      => 'Đăng ký thành công',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }

    /**
     * Xử lý request đăng nhập người dùng.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message'      => 'Đăng nhập thành công',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    /**
     * Lấy thông tin của người dùng hiện tại đã đăng nhập.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * Đăng xuất người dùng bằng cách xóa token hiện tại.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }
}
