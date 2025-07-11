<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Kiểm tra xem người dùng có quyền cụ thể hay không.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$permissions
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $user = Auth::user();

        // Nếu chưa đăng nhập, chuyển về trang login
        if (!$user) {
            return redirect()->route('login');
        }

        // Lấy tất cả quyền từ các vai trò của người dùng
        $userPermissions = $user->roles()
            ->with('permissions') // eager load permissions
            ->get()
            ->pluck('permissions') // lấy từng danh sách permissions
            ->flatten()            // gộp lại thành một collection
            ->pluck('name')        // lấy tên quyền
            ->unique()
            ->toArray();

        // Nếu người dùng có ít nhất một quyền phù hợp thì cho phép tiếp tục
        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return $next($request);
            }
        }

        // Không có quyền phù hợp
        abort(403, 'Bạn không có quyền truy cập chức năng này.');
    }
}
