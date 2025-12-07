<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Alias cho middleware role
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // 2. QUAN TRỌNG: CẤU HÌNH ĐÍCH ĐẾN KHI ĐÃ ĐĂNG NHẬP
        // Đây là chỗ quyết định việc bạn bị đá về đâu nếu đã login rồi
        $middleware->redirectUsersTo(function () {
            $user = Auth::user();
            
            // Nếu lỗi session -> về login
            if (!$user) return '/login';

            // Admin -> Dashboard
            if ($user->role == 1) return '/admin/dashboard';
            
            // Giáo viên -> BẮT BUỘC VỀ DASHBOARD
            if ($user->role == 2) return '/teacher/dashboard';

            // Sinh viên -> Đăng ký
            if ($user->role == 0) return '/student/register-class';

            return '/';
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();