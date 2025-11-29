<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Sau khi đăng ký xong thì chuyển hướng về đâu?
     * Chuyển về trang chủ, sau đó middleware sẽ tự đá về trang sinh viên.
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 1. Validate dữ liệu nhập vào
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
            // --- THÊM DÒNG NÀY: Bắt buộc nhập Mã SV và không được trùng ---
            'code' => ['required', 'string', 'max:20', 'unique:users'], 
        ]);
    }

    /**
     * 2. Lưu vào Database
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            
            // --- QUAN TRỌNG: Lưu Mã SV ---
            'code' => $data['code'],
            
            // --- QUAN TRỌNG: Cố định Role = 0 (Sinh viên) ---
            'role' => 0, 
            
            // Các trường khác để null hoặc mặc định
            'status' => 1,
        ]);
    }
}