<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Trả về danh sách tất cả người dùng (dành cho Admin).
     * Tương ứng với route: GET /api/v1/admin/users
     */
    public function index()
    {
        $users = User::with('roles:id,name')->latest()->paginate(15);
        return response()->json($users);
    }

    /**
     * Trả về thông tin chi tiết của tài khoản đang đăng nhập.
     * Tương ứng với route: GET /api/v1/me
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('roles:id,name', 'addresses');

        $defaultAddress = $user->addresses->firstWhere('is_default', true);

        $imageUrl = $user->image_profile
            ? asset(Storage::url($user->image_profile))
            : null;

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'birthday' => $user->birthday,
                'gender' => $user->gender,
                'is_active' => (bool) $user->is_active,
                'image_url' => $imageUrl,
                'created_at' => $user->created_at,
                'roles' => $user->roles->pluck('name'),
                'default_address' => $defaultAddress,
                'addresses' => $user->addresses,
            ]
        ]);
    }
}
