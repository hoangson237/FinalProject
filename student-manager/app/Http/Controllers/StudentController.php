<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // 1. Trang Đăng ký (Danh sách tất cả lớp mở)
    public function index(Request $request)
    {
        $query = Classroom::with('teacher')->where('status', 1);

        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('name', 'LIKE', "%{$request->keyword}%");
        }

        $classes = $query->latest()->paginate(10)->withQueryString();

        // Lấy danh sách ID các lớp đã đăng ký để ẩn nút
        $my_registered_ids = Registration::where('student_id', Auth::id())
                                ->pluck('classroom_id')
                                ->toArray();

        return view('student.index', compact('classes', 'my_registered_ids'));
    }

    // 2. Hành động Đăng ký
    public function store($class_id)
    {
        $user = Auth::user();
        $class = Classroom::findOrFail($class_id);

        // Check trùng
        if (in_array($class_id, $user->registrations->pluck('classroom_id')->toArray())) {
            return back()->with('error', 'Bạn đã đăng ký lớp này rồi!');
        }

        // Check sĩ số
        if ($class->current_quantity >= $class->max_quantity) {
            return back()->with('error', 'Lớp đã hết chỗ!');
        }

        // Tạo đăng ký
        Registration::create([
            'student_id' => $user->id,
            'classroom_id' => $class->id,
            'score' => null
        ]);

        // Tăng sĩ số
        $class->increment('current_quantity');

       // Sửa dòng return cuối cùng của hàm store:
return redirect()->route('student.myClasses')
    ->with('success', 'Đăng ký thành công! Bạn có thể theo dõi kết quả học tập bên dưới.');
    }

    // 3. [MỚI] Trang "Lớp học của tôi" (Xem điểm & Hủy)
    public function myClasses()
    {
        $registrations = Registration::with(['classroom.teacher'])
                            ->where('student_id', Auth::id())
                            ->latest()
                            ->get();

        return view('student.my_classes', compact('registrations'));
    }

    // 4. [MỚI] Hủy đăng ký
    public function cancel($id)
    {
        $reg = Registration::where('id', $id)->where('student_id', Auth::id())->firstOrFail();
        
        // Nếu đã có điểm thì không cho hủy
        if ($reg->score !== null) {
            return back()->with('error', 'Lớp này đã có điểm, không thể hủy!');
        }

        // Giảm sĩ số lớp
        $reg->classroom->decrement('current_quantity');
        
        // Xóa
        $reg->delete();

        return back()->with('success', 'Đã hủy đăng ký lớp học.');
    }
}