<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Middleware kiểm tra nếu user có 1 trong các vai trò được truyền vào.
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.auths.login');
        }

        $userRoles = $user->roles->pluck('slug')->toArray();

        if (!array_intersect($roles, $userRoles)) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
