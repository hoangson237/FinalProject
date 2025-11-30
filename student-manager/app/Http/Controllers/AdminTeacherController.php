<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminTeacherController extends Controller
{
    // 1. Danh sách Giáo viên (Có tìm kiếm & Phân trang)
    public function index(Request $request)
    {
        $query = User::where('role', 2); // Role 2 = Giáo viên

        // Logic Tìm kiếm
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('email', 'LIKE', "%{$keyword}%")
                  ->orWhere('code', 'LIKE', "%{$keyword}%");
            });
        }
        
        // Phân trang 10 người/trang
        $teachers = $query->latest()->paginate(10)->withQueryString();
        return view('admin.teachers.index', compact('teachers'));
    }

    // 2. Form Thêm mới
    public function create()
    {
        return view('admin.teachers.create');
    }

    // 3. Lưu Giáo viên mới (Store)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'code' => 'required|string|max:20|unique:users,code',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Thông báo lỗi Tiếng Việt
            'name.required' => 'Họ tên giáo viên không được để trống!',
            'email.unique' => 'Email này đã được đăng ký!',
            'code.required' => 'Mã giáo viên là bắt buộc!',
            'code.unique' => 'Mã giáo viên này đã tồn tại!',
            'avatar.max' => 'Ảnh quá lớn (Tối đa 2MB)!',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make('12345678'); // Pass mặc định 8 số
        $data['role'] = 2; // GÁN CỨNG ROLE = GIÁO VIÊN
        $data['status'] = 1;

        // Xử lý Upload Ảnh
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Đã thêm giáo viên thành công!');
    }

    // 4. Form Sửa
    public function edit($id)
    {
        $teacher = User::findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    // 5. Cập nhật (Update)
    public function update(Request $request, $id)
    {
        $teacher = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id, // Bỏ qua ID hiện tại
            'code' => 'required|string|max:20|unique:users,code,'.$id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Họ tên không được để trống!',
            'email.unique' => 'Email này đã có người khác sử dụng!',
            'code.unique' => 'Mã giáo viên bị trùng!',
        ]);

        $data = $request->all();

        // Xử lý ảnh: Nếu có upload ảnh mới -> Xóa ảnh cũ
        if ($request->hasFile('avatar')) {
            if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
                Storage::disk('public')->delete($teacher->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Cập nhật giáo viên thành công!');
    }

    // 6. Xóa (Destroy)
    public function destroy($id)
    {
        $teacher = User::findOrFail($id);
        
        // Xóa ảnh vật lý để sạch server
        if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
            Storage::disk('public')->delete($teacher->avatar);
        }

        $teacher->delete(); // Soft Delete
        
        return back()->with('success', 'Đã chuyển giáo viên vào thùng rác!');
    }
}