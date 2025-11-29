<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminStudentController extends Controller
{
    // 1. Danh sách sinh viên
   public function index(Request $request) // <--- Nhớ thêm Request $request
{
    // 1. Khởi tạo query lấy Sinh viên (Role 0)
    $query = User::where('role', 0);

    // 2. Nếu có từ khóa tìm kiếm -> Lọc theo Tên hoặc Mã hoặc Email
    if ($request->has('keyword') && $request->keyword != '') {
        $keyword = $request->keyword;
        $query->where(function($q) use ($keyword) {
            $q->where('name', 'LIKE', "%{$keyword}%")
              ->orWhere('email', 'LIKE', "%{$keyword}%")
              ->orWhere('code', 'LIKE', "%{$keyword}%");
        });
    }

    // 3. Sắp xếp & Phân trang (Giữ lại tham số tìm kiếm khi chuyển trang)
    $students = $query->latest()->paginate(10)->withQueryString();

    return view('admin.students.index', compact('students'));
}

    // 2. Form thêm mới
    public function create()
    {
        return view('admin.students.create');
    }

    // 3. Lưu sinh viên mới (Có Upload ảnh)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'code' => 'required|unique:users',
            'avatar' => 'nullable|image|max:2048' // Validate ảnh tối đa 2MB
        ]);

        $data = $request->all();
        $data['password'] = Hash::make('123456'); // Mặc định pass
        $data['role'] = 0; // Role Sinh viên
        $data['status'] = 1;

        // Xử lý Upload Ảnh
        if ($request->hasFile('avatar')) {
            // Lưu vào storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Thêm sinh viên thành công!');
    }

    // 4. Form sửa
    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // 5. Cập nhật (Có xóa ảnh cũ thay ảnh mới)
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            // unique nhưng bỏ qua ID hiện tại
            'email' => 'required|email|unique:users,email,'.$id,
            'code' => 'required|unique:users,code,'.$id,
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        // Xử lý ảnh: Nếu có upload ảnh mới
        if ($request->hasFile('avatar')) {
            // 1. Xóa ảnh cũ (nếu có)
            if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
                Storage::disk('public')->delete($student->avatar);
            }
            // 2. Lưu ảnh mới
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Cập nhật thành công!');
    }

    // 6. Xóa (Soft Delete)
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete(); // Soft delete (chỉ ẩn đi, không xóa thật)
        
        return back()->with('success', 'Đã chuyển sinh viên vào thùng rác!');
    }
}