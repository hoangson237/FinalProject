<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Classroom;
use Carbon\Carbon; // <--- QUAN TRỌNG: Để xử lý ngày tháng

class StudentController extends Controller
{
    // 1. TRANG ĐĂNG KÝ (View là student.index)
    public function index(Request $request)
    {
        $query = Classroom::with('teacher')->where('status', 1);

        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('name', 'LIKE', "%{$request->keyword}%");
        }

        // --- SỬA TÊN BIẾN Ở ĐÂY CHO KHỚP VỚI VIEW CỦA BẠN ---
        $my_registered_ids = Registration::where('student_id', Auth::id())
                                         ->pluck('classroom_id')
                                         ->toArray();

        $classes = $query->latest()->paginate(10)->withQueryString();

        // Trả về view 'student.index' với biến '$my_registered_ids'
        return view('student.index', compact('classes', 'my_registered_ids'));
    }

    // 2. XỬ LÝ ĐĂNG KÝ
    public function store($id)
    {
        $class = Classroom::findOrFail($id);
        
        // Check sĩ số
        if ($class->current_quantity >= $class->max_quantity) {
            return back()->with('error', 'Lớp đã đầy sĩ số!');
        }

        // Check trùng
        $exists = Registration::where('student_id', Auth::id())
                              ->where('classroom_id', $id)
                              ->exists();
        if ($exists) {
            return back()->with('error', 'Bạn đã đăng ký lớp này rồi.');
        }

        // Tạo đăng ký
        Registration::create([
            'student_id' => Auth::id(),
            'classroom_id' => $id,
            'score' => null
        ]);

        // Tăng sĩ số
        $class->increment('current_quantity');

        return back()->with('success', 'Đăng ký thành công!');
    }

    // 3. DANH SÁCH LỚP CỦA TÔI
    public function myClasses()
    {
        $student_id = Auth::id();
        $registrations = Registration::with(['classroom.teacher'])
                                     ->where('student_id', $student_id)
                                     ->latest()
                                     ->paginate(10);

        return view('student.my_classes', compact('registrations'));
    }

    // --- 4. HỦY ĐĂNG KÝ (LOGIC CHẶT: TRƯỚC 3 NGÀY) ---
    public function cancel($id)
    {
        $reg = Registration::where('id', $id)
                           ->where('student_id', Auth::id())
                           ->firstOrFail();

        // 1. Nếu đã có điểm -> Cấm hủy
        if ($reg->score !== null) {
            return back()->with('error', 'Không thể hủy lớp đã có điểm tổng kết.');
        }

        // 2. LOGIC NGÀY THÁNG
        $class = $reg->classroom;
        
        if ($class->start_date) {
            $startDate = Carbon::parse($class->start_date);
            
            // QUY ĐỊNH: Phải hủy trước 3 ngày
            // Ví dụ: Học ngày 15, thì hạn chót là hết ngày 12.
            $deadline = $startDate->copy()->subDays(3)->endOfDay();

            // Nếu Hiện tại > Hạn chót -> Cấm hủy
            if (now()->greaterThan($deadline)) {
                return back()->with('error', 'Đã quá hạn hủy! Quy chế: Chỉ được hủy trước ngày bắt đầu 3 ngày.');
            }
        }

        // 3. Thực hiện Hủy
        $class->decrement('current_quantity');
        $reg->delete(); 

        return back()->with('success', 'Đã hủy đăng ký thành công.');
    }
}