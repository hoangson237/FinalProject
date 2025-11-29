<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    // 1. Xem danh sách các lớp mình được phân công dạy
    public function index()
    {
        // Chỉ lấy lớp mà teacher_id trùng với ID người đang đăng nhập
        $classes = Classroom::where('teacher_id', Auth::id())->get();
        return view('teacher.index', compact('classes'));
    }

    // 2. Xem danh sách sinh viên trong 1 lớp cụ thể
    public function show($id)
    {
        $class = Classroom::with('registrations.student')->findOrFail($id);
        
        // Bảo mật: Nếu lớp này không phải của mình thì chặn
        if ($class->teacher_id != Auth::id()) {
            abort(403, 'Bạn không có quyền quản lý lớp này');
        }

        return view('teacher.show', compact('class'));
    }

    // 3. Xử lý nhập điểm (Cập nhật bảng Registrations)
    public function updateScore(Request $request, $registration_id)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:10' // Điểm từ 0 đến 10
        ]);

        $reg = Registration::findOrFail($registration_id);
        $reg->score = $request->score;
        $reg->save();

        return back()->with('success', 'Đã cập nhật điểm số!');
    }
}