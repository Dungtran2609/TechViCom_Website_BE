<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Trả về danh sách người dùng có vai trò là "user" (khách hàng).
     */
    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })
            ->with(['roles:id,name']) // Chỉ lấy id và name của role
            ->select('id', 'name', 'email', 'image_profile', 'is_active')
            ->get();

        return response()->json($users, 200);
    }
}
