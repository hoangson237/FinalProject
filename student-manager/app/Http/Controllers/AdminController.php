<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Registration; // <--- BẮT BUỘC CÓ DÒNG NÀY

class AdminController extends Controller
{
    // --- MODULE 1: DASHBOARD (TỔNG QUAN) ---
    public function index()
    {
        // 1. Thống kê số liệu cho 3 thẻ màu
        $stats = [
            'students' => User::where('role', 0)->count(),
            'teachers' => User::where('role', 2)->count(),
            'classes'  => Classroom::count(),
        ];

        // 2. Lấy 5 hoạt động đăng ký mới nhất (Để hiện bảng bên dưới)
        $recent_registrations = Registration::with(['student', 'classroom'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact('stats', 'recent_registrations'));
    }

    // --- MODULE 2: QUẢN LÝ LỚP HỌC (CRUD) ---

    // Danh sách Lớp (Trang riêng)
    public function classList(Request $request)
    {
        $query = Classroom::with('teacher');

        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('name', 'LIKE', "%{$request->keyword}%");
        }
        if ($request->has('teacher_id') && $request->teacher_id != '') {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $classrooms = $query->latest()->paginate(10)->withQueryString();
        $teachers = User::where('role', 2)->get();

        return view('admin.classes.index', compact('classrooms', 'teachers'));
    }

    // Form Thêm mới
    public function create()
    {
        $teachers = User::where('role', 2)->get(); 
        return view('admin.create', compact('teachers'));
    }

    // Lưu lớp mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:users,id',
            'max_quantity' => 'required|integer|min:1|max:20' 
        ], [
            'max_quantity.max' => 'Sĩ số lớp không được vượt quá 20 người!'
        ]);

        Classroom::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'max_quantity' => $request->max_quantity,
            'current_quantity' => 0,
            'status' => 1
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Đã tạo lớp học thành công!');
    }

    // Form Sửa
    public function edit($id)
    {
        $class = Classroom::findOrFail($id);
        $teachers = User::where('role', 2)->get();
        return view('admin.edit', compact('class', 'teachers'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $class = Classroom::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:users,id',
            'max_quantity' => 'required|integer|min:1|max:20'
        ], [
            'max_quantity.max' => 'Sĩ số lớp không được vượt quá 20 người!'
        ]);

        $class->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'max_quantity' => $request->max_quantity,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Cập nhật lớp học thành công!');
    }

    // Xóa lớp
    public function destroy($id)
    {
        $class = Classroom::findOrFail($id);
        $class->delete();
        return back()->with('success', 'Đã xóa lớp học!');
    }
}