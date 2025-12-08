<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Registration;

class AdminController extends Controller
{
    // --- MODULE 1: DASHBOARD ---
    public function index()
    {
        $stats = [
            'students' => User::where('role', 0)->count(),
            'teachers' => User::where('role', 2)->count(),
            'classes'  => Classroom::count(),
        ];

        $recent_registrations = Registration::with(['student', 'classroom'])
                                        ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_registrations'));
    }

    // --- MODULE 2: QUẢN LÝ LỚP HỌC ---

    // Danh sách lớp
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
            if ($request->status == '1') {
                $query->where('status', 1)->whereColumn('current_quantity', '<', 'max_quantity');
            } elseif ($request->status == '0') {
                $query->where(function($q) {
                    $q->where('status', 0)->orWhereColumn('current_quantity', '>=', 'max_quantity');
                });
            }
        }

        $classrooms = $query->latest()->paginate(10)->withQueryString();
        $teachers = User::where('role', 2)->get();

        return view('admin.classes.index', compact('classrooms', 'teachers'));
    }

    // Form thêm mới
    public function create()
    {
        $teachers = User::where('role', 2)->get(); 
        if (view()->exists('admin.classes.create')) {
            return view('admin.classes.create', compact('teachers'));
        }
        return view('admin.create', compact('teachers'));
    }

    // --- HÀM LƯU MỚI (STORE) ---
    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:users,id',
            'max_quantity' => 'required|integer|min:1|max:50', // Chặn backend Max 50
            'start_date' => 'required|date',
            'days' => 'required|array|min:1', // Bắt buộc chọn ít nhất 1 ngày
            'shift' => 'required|string',
            'room' => 'required|string',
        ], [
            'days.required' => 'Vui lòng chọn ít nhất 1 ngày học.',
            'max_quantity.max' => 'Sĩ số lớp tối đa là 50 sinh viên.',
            'room.required' => 'Vui lòng chọn phòng học.',
        ]);

        // 2. Ghép chuỗi Lịch học (VD: "T2, T4, T6 (Ca 1)")
        // Hàm implode sẽ nối mảng ['T2', 'T4'] thành chuỗi "T2, T4"
        $daysString = implode(', ', $request->days);
        $finalSchedule = $daysString . ' (' . $request->shift . ')';

        // 3. Lưu vào DB
        Classroom::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'max_quantity' => $request->max_quantity,
            'current_quantity' => 0,
            'status' => 1,
            'start_date' => $request->start_date,
            'schedule'   => $finalSchedule, // Lưu chuỗi đã ghép
            'room'       => $request->room,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Đã tạo lớp học thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $class = Classroom::findOrFail($id);
        $teachers = User::where('role', 2)->get();
        if (view()->exists('admin.classes.edit')) {
            return view('admin.classes.edit', compact('class', 'teachers'));
        }
        return view('admin.edit', compact('class', 'teachers'));
    }

    // --- HÀM CẬP NHẬT (UPDATE) ---
    public function update(Request $request, $id)
    {
        $class = Classroom::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:users,id',
            'max_quantity' => 'required|integer|min:1|max:50', // Chặn backend Max 50
            'status' => 'required',
            'start_date' => 'required|date',
            'days' => 'required|array|min:1', 
            'shift' => 'required|string',
            'room' => 'required|string',
        ], [
            'days.required' => 'Vui lòng chọn ít nhất 1 ngày học.',
            'max_quantity.max' => 'Sĩ số lớp tối đa là 50 sinh viên.',
        ]);

        // Check Logic Lớp đầy
        $newMaxQty = $request->max_quantity;
        $newStatus = $request->status;
        if ($newStatus == 1 && $class->current_quantity >= $newMaxQty) {
            $newStatus = 0; 
        }

        // Ghép chuỗi Lịch học
        $daysString = implode(', ', $request->days);
        $finalSchedule = $daysString . ' (' . $request->shift . ')';

        $class->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'max_quantity' => $newMaxQty,
            'status' => $newStatus, 
            'start_date' => $request->start_date,
            'schedule'   => $finalSchedule,
            'room'       => $request->room,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Cập nhật lớp học thành công!');
    }

    // --- KHU VỰC SOFT DELETE ---

    // 1. Xóa mềm (Hàm destroy cũ của bạn đã OK rồi, chỉ cần giữ nguyên)
    public function destroy($id)
    {
        $class = Classroom::findOrFail($id);
        $class->delete();
        return back()->with('success', 'Đã chuyển lớp học vào thùng rác!');
    }

    // 2. Xem thùng rác
    public function trash()
    {
        $deletedClasses = Classroom::onlyTrashed()->with('teacher')->latest()->paginate(10);
        // Đảm bảo file view tồn tại ở resources/views/admin/classes/trash.blade.php
        return view('admin.classes.trash', compact('deletedClasses'));
    }

    // 3. Khôi phục
    public function restore($id)
    {
        $class = Classroom::withTrashed()->findOrFail($id);
        $class->restore(); 
        
        return redirect()->route('admin.classes.index')->with('success', 'Đã khôi phục lớp học thành công!');
    }

    // 4. Xóa vĩnh viễn
    public function forceDelete($id)
    {
        $class = Classroom::withTrashed()->findOrFail($id);
        
        // Trước khi xóa vĩnh viễn, nên xóa sạch dữ liệu liên quan
        $class->registrations()->delete(); 
        
        $class->forceDelete(); 

        return back()->with('success', 'Đã xóa vĩnh viễn lớp học!');
    }
}