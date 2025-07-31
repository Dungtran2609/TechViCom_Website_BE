<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Middleware kiểm tra xem user có quyền cụ thể không
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $permission Tên quyền cần kiểm tra (ví dụ: 'assign_permission')
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();

        // Nếu chưa đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }

        // Kiểm tra người dùng có quyền được yêu cầu không
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        // Nếu không có quyền, từ chối truy cập
        abort(403, 'Bạn không có quyền truy cập: ' . $permission);
    }
}
