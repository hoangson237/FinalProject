<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth; // <--- Nhớ thêm dòng này

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Khai báo Middleware phân quyền (Bạn đã làm)
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // 2. XỬ LÝ LỖI LOOP: Điều hướng khi người dùng ĐÃ ĐĂNG NHẬP
        // (Thay thế cho file RedirectIfAuthenticated cũ)
        $middleware->redirectUsersTo(function () {
            // Lấy role hiện tại
            $user = Auth::user();
            
            // Nếu chưa lấy được user (lỗi session) thì về login
            if (!$user) return '/login'; 

            $role = $user->role;

            if ($role == 1) return '/admin/dashboard';
            if ($role == 2) return '/teacher/classes';
            if ($role == 0) return '/student/register-class';

            return '/';
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();