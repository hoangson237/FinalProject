<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    // 1. Hiển thị Form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // 2. Xử lý Đăng ký (Tên hàm chuẩn: register)
    public function register(Request $request)
    {
        // --- VALIDATE ---
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'code'     => ['required', 'string', 'max:20', 'unique:users'],
            'phone'    => ['required', 'numeric', 'digits_between:10,11'],
            'address'  => ['required', 'string', 'max:255'],
            // Bắt buộc 8 ký tự
            'password' => ['required', 'string', 'min:8', 'max:8', 'confirmed'],
        ], [
            'password.min' => 'Mật khẩu phải có ĐÚNG 8 ký tự.',
            'password.max' => 'Mật khẩu phải có ĐÚNG 8 ký tự.',
            'email.unique' => 'Email này đã tồn tại.',
            'code.unique'  => 'Mã sinh viên đã tồn tại.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // --- TẠO USER ---
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'code'     => $request->code,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'password' => Hash::make($request->password),
            'role'     => 0, 
            'status'   => 1,
        ]);

        event(new Registered($user));

        // --- CHẶN LOGIN & XÓA SESSION ---
        if (Auth::check()) {
            Auth::logout();
            Session::flush();
        }

        // --- VỀ TRANG LOGIN ---
        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}