<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminTeacherController extends Controller
{
    // 1. Danh sách giáo viên (Đích đến của thẻ Tổng Giáo viên)
    public function index(Request $request)
    {
        $query = User::where('role', 2); // Chỉ lấy Giáo viên

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('email', 'LIKE', "%{$keyword}%")
                  ->orWhere('code', 'LIKE', "%{$keyword}%");
            });
        }
        
        $teachers = $query->latest()->paginate(10)->withQueryString();
        return view('admin.teachers.index', compact('teachers'));
    }

    // 2. Form thêm mới
    public function create()
    {
        return view('admin.teachers.create');
    }

    // 3. Lưu giáo viên mới (Store)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'code' => 'required|unique:users',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make('123456'); // Mật khẩu mặc định
        $data['role'] = 2; // GÁN CỨNG ROLE = GIÁO VIÊN (2)
        $data['status'] = 1;
        
        // Xử lý Upload Ảnh
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // TẠO USER (Lệnh này chỉ thành công khi User.php có đủ $fillable)
        User::create($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Đã thêm giáo viên thành công!');
    }

    // 4. Form sửa
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
            'name' => 'required',
            // unique nhưng bỏ qua ID hiện tại
            'email' => 'required|email|unique:users,email,'.$id,
            'code' => 'required|unique:users,code,'.$id,
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        // Xử lý ảnh: Xóa ảnh cũ nếu có upload ảnh mới
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

    // 6. Xóa (Soft Delete)
    public function destroy($id)
    {
        $teacher = User::findOrFail($id);
        
        // Cần xóa cả ảnh trước khi xóa user (UX tốt)
        if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
            Storage::disk('public')->delete($teacher->avatar);
        }

        $teacher->delete(); 
        
        return back()->with('success', 'Đã chuyển giáo viên vào thùng rác!');
    }
}