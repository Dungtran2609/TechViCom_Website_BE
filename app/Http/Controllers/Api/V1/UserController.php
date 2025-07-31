<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Trả về thông tin tài khoản đang đăng nhập.
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('roles:id,name');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'image_profile' => $user->image_profile,
            'is_active' => $user->is_active,
            // 'roles' => $user->roles,
        ]);
    }
}
