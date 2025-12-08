<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * --- HÀM XỬ LÝ ĐĂNG NHẬP CHÍNH ---
     * Đã sửa lại logic chuyển hướng dùng route name chuẩn xác.
     */
public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            
            // 1. XÓA SẠCH LỊCH SỬ URL CŨ (Quan trọng để không bị đẩy về trang classes)
            $request->session()->forget('url.intended');

            $user = Auth::user();
            
            // 2. PHÂN QUYỀN CHUYỂN HƯỚNG (Dùng route name cho chuẩn xác)
            if ($user->role == 1) {
                // Admin -> Dashboard Admin
                $targetUrl = route('admin.dashboard');
            } 
            elseif ($user->role == 2) {
                // Teacher -> Dashboard Giáo viên
                $targetUrl = route('teacher.dashboard');
            } 
            elseif ($user->role == 0) {
                // Student -> Trang Đăng ký (Hoặc trang Lớp của tôi tùy bạn)
                $targetUrl = route('student.register');
            } else {
                // Dự phòng
                $targetUrl = '/';
            }

            // 3. CHUYỂN HƯỚNG VÀ CẤM CACHE
            return redirect($targetUrl)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * --- HÀM BÁO LỖI TIẾNG VIỆT ---
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Tài khoản hoặc mật khẩu không đúng.'],
        ]);
    }
}