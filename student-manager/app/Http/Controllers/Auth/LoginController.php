<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // --- PHẦN BẠN CẦN THÊM VÀO ĐÂY ---
    
    /**
     * Ghi đè phương thức điều hướng sau khi đăng nhập
     */
    protected function redirectTo()
    {
        // Lấy quyền của user đang đăng nhập
        $role = auth()->user()->role; 
        
        // 1. Nếu là Admin -> Vào Dashboard quản lý
        if ($role == 1) {
            return '/admin/dashboard';
        }
        
        // 2. Nếu là Giáo viên -> Vào danh sách lớp dạy
        if ($role == 2) {
            return '/teacher/classes';
        }
        
        // 3. Nếu là Sinh viên -> Vào trang đăng ký
        if ($role == 0) {
            return '/student/register-class';
        }
        
        // Mặc định về trang chủ nếu không khớp role nào
        return '/';
    }
    // ---------------------------------
}