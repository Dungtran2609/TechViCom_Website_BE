<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Chỉ cho phép người dùng có vai trò 'admin' được truy cập.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Nếu chưa đăng nhập, chuyển về trang login
        if (!$user) {
            return redirect()->route('login');
        }

        // Kiểm tra user có vai trò là 'admin' không
        $hasAdminRole = $user->roles()->where('name', ['admin', 'staff'])->exists();

        if ($hasAdminRole) {
            return $next($request);
        }

        // Nếu không có quyền admin
        abort(403, 'Bạn không có quyền truy cập (chỉ dành cho Admin).');
    }
}
