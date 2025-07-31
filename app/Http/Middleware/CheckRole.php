<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Middleware kiểm tra nếu user có 1 trong các vai trò được truyền vào.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Nếu chưa đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }

        // Lấy danh sách slug các vai trò của user
        $userRoles = $user->roles->pluck('slug')->toArray();

        // Nếu không có bất kỳ vai trò nào khớp
        if (!array_intersect($roles, $userRoles)) {
            abort(403, 'Bạn không có quyền truy cập (cần vai trò: ' . implode(', ', $roles) . ')');
        }

        return $next($request);
    }
}
