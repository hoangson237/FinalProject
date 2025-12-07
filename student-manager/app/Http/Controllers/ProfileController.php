<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // 1. Hiển thị form
    public function edit()
    {
        $user = Auth::user();
        // Tự động chọn layout tương ứng
        $layout = $user->role == 1 ? 'layouts.admin' : 'layouts.portal';
        return view('profile.edit', compact('user', 'layout'));
    }

    // 2. Cập nhật thông tin
   public function update(Request $request)
    {
        $user = Auth::user();

        // --- VALIDATE ---
        $rules = [
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone'    => 'nullable|numeric|digits_between:10,11',
            'address'  => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|max:8|confirmed',
        ];

        if ($user->role == 1) {
            $rules['name'] = 'required|string|max:255';
        }

        $request->validate($rules, [
            'password.min' => 'Mật khẩu phải có ĐÚNG 8 ký tự.',
            'password.max' => 'Mật khẩu phải có ĐÚNG 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'phone.numeric' => 'Số điện thoại phải là số.',
            'avatar.max' => 'Ảnh quá lớn (Max 2MB).',
            'name.required' => 'Họ tên không được để trống.',
        ]);

        // --- UPDATE DATA ---
        $data = [
            'phone'   => $request->phone,
            'address' => $request->address,
        ];

        if ($user->role == 1) {
            $data['name'] = $request->name;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        // --- CHUYỂN HƯỚNG VỀ TRANG CHỦ TỪNG ROLE (KÈM THÔNG BÁO) ---
        
        $msg = 'Cập nhật hồ sơ thành công!';

        if ($user->role == 1) {
            // Admin -> Về Dashboard
            return redirect()->route('admin.dashboard')->with('success', $msg);
        } elseif ($user->role == 2) {
            // Giáo viên -> Về Danh sách lớp
            return redirect()->route('teacher.dashboard')->with('success', $msg);
        } else {
            // Sinh viên -> Về trang Đăng ký môn
            return redirect()->route('student.register')->with('success', $msg);
        }
    }
}