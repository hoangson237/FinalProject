<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Thư viện để xóa ảnh
use App\Exports\StudentsExport;


class AdminStudentController extends Controller
{
    // 1. Danh sách
    public function index(Request $request)
    {
        $query = User::where('role', 0); // Lấy SV

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('email', 'LIKE', "%{$keyword}%")
                  ->orWhere('code', 'LIKE', "%{$keyword}%");
            });
        }

        $students = $query->latest()->paginate(2)->withQueryString();
        return view('admin.students.index', compact('students'));
    }

    // 2. Form thêm
    public function create()
    {
        return view('admin.students.create');
    }

   // 3. Lưu mới (Store) - CÓ VALIDATE TIẾNG VIỆT
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'code' => 'required|string|max:20|unique:users,code',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // --- DỊCH TIẾNG VIỆT ---
            'name.required' => 'Họ tên không được để trống!',
            'email.required' => 'Email là bắt buộc!',
            'email.email' => 'Định dạng email không đúng!',
            'email.unique' => 'Email này đã có người khác sử dụng!',
            'code.required' => 'Mã sinh viên là bắt buộc!',
            'code.unique' => 'Mã sinh viên này đã tồn tại!',
            'avatar.image' => 'File tải lên phải là hình ảnh!',
            'avatar.max' => 'Ảnh quá lớn (Tối đa 2MB)!',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make('12345678'); 
        $data['role'] = 0;
        $data['status'] = 1;

        if ($request->hasFile('avatar')) {
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

    // 5. Cập nhật (Update) - CÓ THÔNG BÁO TIẾNG VIỆT
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        // --- PHẦN SỬA: Thêm mảng thông báo tiếng Việt vào tham số thứ 2 ---
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'code' => 'required|string|max:20|unique:users,code,'.$id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // --- CÁC DÒNG DỊCH TIẾNG VIỆT ---
            'name.required' => 'Họ tên không được để trống!',
            'email.required' => 'Email là bắt buộc!',
            'email.email' => 'Định dạng email không đúng!',
            'email.unique' => 'Email này đã có người khác sử dụng!',
            'code.required' => 'Mã sinh viên là bắt buộc!',
            'code.unique' => 'Mã sinh viên này đã tồn tại!',
            'avatar.image' => 'File tải lên phải là hình ảnh!',
            'avatar.max' => 'Ảnh quá lớn (Tối đa 2MB)!',
        ]);
        // ------------------------------------------------------------------

        $data = $request->all();

        // Xử lý ảnh (Giữ nguyên)
        if ($request->hasFile('avatar')) {
            if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
                Storage::disk('public')->delete($student->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Cập nhật thành công!');
    }

    // 6. Xóa (Destroy) - CÓ XÓA ẢNH VẬT LÝ
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        
        // Xóa ảnh đại diện trong folder trước khi xóa user
        if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
            Storage::disk('public')->delete($student->avatar);
        }

        $student->delete(); 
        
        return back()->with('success', 'Đã xóa sinh viên và ảnh đại diện!');
    }
}