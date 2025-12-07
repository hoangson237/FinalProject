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
     * Ghi đè logic mặc định để kiểm soát hoàn toàn việc chuyển hướng.
     */
public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            $request->session()->forget('url.intended');

            $user = Auth::user();
            $targetUrl = '/';

            if ($user->role == 1) $targetUrl = '/admin/dashboard';
            if ($user->role == 2) $targetUrl = '/teacher/dashboard';
            if ($user->role == 0) $targetUrl = route('student.register');

            // --- LỆNH CẤM CACHE CHO CỐC CỐC ---
            return redirect($targetUrl)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
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