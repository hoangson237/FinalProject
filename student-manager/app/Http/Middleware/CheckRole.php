<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Nếu chưa đăng nhập -> Đá về trang Login
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;

        // 2. Kiểm tra quyền (Chặn nếu sai quyền)
        if ($role == 'admin' && $userRole != 1) {
            abort(403, 'Bạn không có quyền truy cập trang Admin!');
        }

        if ($role == 'teacher' && $userRole != 2) {
            abort(403, 'Bạn không có quyền truy cập trang Giáo viên!');
        }

        if ($role == 'student' && $userRole != 0) {
            abort(403, 'Bạn không có quyền truy cập trang Sinh viên!');
        }

        return $next($request);
    }
}