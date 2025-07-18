<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Xử lý middleware kiểm tra vai trò người dùng
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.auths.login');
        }

        $userRoles = $user->roles->pluck('slug')->toArray();
        // Nếu người dùng không có bất kỳ role nào trong danh sách
        if (!array_intersect($roles, $userRoles)) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
