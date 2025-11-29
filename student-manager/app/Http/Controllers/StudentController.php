<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // 1. Hiển thị danh sách lớp để đăng ký
    public function index()
    {
        // Lấy tất cả lớp đang MỞ (status = 1)
        $classes = Classroom::with('teacher')->where('status', 1)->get();

        // Lấy danh sách ID các lớp mà sinh viên này ĐÃ đăng ký (để ẩn nút đăng ký đi)
        $my_registered_ids = Registration::where('student_id', Auth::id())
                                ->pluck('classroom_id')
                                ->toArray();

        return view('student.index', compact('classes', 'my_registered_ids'));
    }

    // 2. Xử lý hành động Đăng ký (Logic quan trọng nhất)
    public function store($class_id)
    {
        $user = Auth::user();
        $class = Classroom::findOrFail($class_id);

        // --- KIỂM TRA 1: Đã đăng ký chưa? ---
        if (in_array($class_id, $user->registrations->pluck('classroom_id')->toArray())) {
            return back()->with('error', 'Bạn đã đăng ký lớp này rồi!');
        }

        // --- KIỂM TRA 2: Lớp còn chỗ không? (GIẢI QUYẾT BÀI TOÁN SĨ SỐ) ---
        if ($class->current_quantity >= $class->max_quantity) {
            return back()->with('error', 'Lớp đã hết chỗ! Vui lòng chọn lớp khác.');
        }

        // --- NẾU OK THÌ GHI DANH ---
        // B1: Tạo bản ghi đăng ký
        Registration::create([
            'student_id' => $user->id,
            'classroom_id' => $class->id,
            'score' => null // Chưa có điểm
        ]);

        // B2: Tăng sĩ số lớp lên 1
        $class->increment('current_quantity');

        return back()->with('success', 'Đăng ký thành công lớp: ' . $class->name);
    }
}