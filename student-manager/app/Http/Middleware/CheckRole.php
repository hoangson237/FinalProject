<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Chưa đăng nhập -> Về Login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Bảng quy đổi quyền (Mapping)
        // Thay vì viết nhiều if, ta khai báo 1 mảng định nghĩa
        $roleIds = [
            'student' => 0,
            'admin'   => 1,
            'teacher' => 2,
        ];

        // 3. Kiểm tra logic (Gọn trong 1 nốt nhạc)
        // Nếu role truyền vào không tồn tại trong bảng quy đổi HOẶC role của user không khớp
        if (!isset($roleIds[$role]) || $user->role != $roleIds[$role]) {
            abort(403, 'Bạn không có quyền truy cập trang này!');
        }

        return $next($request);
    }
}