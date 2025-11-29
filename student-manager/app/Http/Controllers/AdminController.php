<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // --- MODULE: TỔNG QUAN (DASHBOARD) ---
    
    // 1. Trang Tổng quan (Chỉ hiện thống kê)
    public function index()
    {
        $stats = [
            'students' => User::where('role', 0)->count(), 
            'teachers' => User::where('role', 2)->count(), 
            'classes' => Classroom::count(), 
        ];
        return view('admin.dashboard', compact('stats'));
    }

    // --- MODULE: QUẢN LÝ LỚP HỌC (CRUD CLASSROOMS) ---

    // 2. Trang Danh sách Lớp học (Đích đến của Menu "Quản lý Lớp học")
    public function classList()
    {
        $classrooms = Classroom::with('teacher')->latest()->paginate(10);
        return view('admin.classes.index', compact('classrooms'));
    }

    // 3. Form Thêm mới 
    public function create()
    {
        $teachers = User::where('role', 2)->get(); 
        return view('admin.create', compact('teachers'));
    }

    // 4. Lưu lớp mới (Store) - ĐÃ SỬA ĐÍCH ĐẾN REDIRECT
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

        // FIX: Chuyển hướng đến trang Danh sách Lớp học
        return redirect()->route('admin.classes.index')->with('success', 'Đã tạo lớp học thành công!');
    }

    // 5. Form Sửa (Edit)
    public function edit($id)
    {
        $class = Classroom::findOrFail($id);
        $teachers = User::where('role', 2)->get();
        return view('admin.edit', compact('class', 'teachers'));
    }

    // 6. Cập nhật (Update)
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

        // FIX: Chuyển hướng đến trang Danh sách Lớp học
        return redirect()->route('admin.classes.index')->with('success', 'Cập nhật lớp học thành công!');
    }

    // 7. Xóa lớp (Destroy)
    public function destroy($id)
    {
        $class = Classroom::findOrFail($id);
        $class->delete();
        return back()->with('success', 'Đã xóa lớp học!');
    }
}