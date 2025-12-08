<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use App\Models\Registration;

class TeacherController extends Controller
{
    // --- 1. DASHBOARD (Trang tổng quan) ---
    public function dashboard()
    {
        $teacher_id = Auth::id();
        
        // Lấy danh sách ID các lớp giáo viên này dạy
        $myClassIds = Classroom::where('teacher_id', $teacher_id)->pluck('id');

        // Thống kê
        $stats = [
            'count_classes'  => $myClassIds->count(),
            'count_students' => Registration::whereIn('classroom_id', $myClassIds)->count(),
            'count_graded'   => Registration::whereIn('classroom_id', $myClassIds)->whereNotNull('score')->count(),
        ];

        // Lấy hoạt động gần đây (Sinh viên mới đăng ký vào lớp của mình)
        $recent_activities = Registration::with(['student', 'classroom'])
            ->whereIn('classroom_id', $myClassIds)
            ->latest()
            ->take(5)
            ->get();

        // Trả về view Dashboard
        return view('teacher.dashboard', compact('stats', 'recent_activities'));
    }

    // --- 2. DANH SÁCH LỚP GIẢNG DẠY ---
    public function index()
    {
        $classes = Classroom::where('teacher_id', Auth::id())
            ->withCount('registrations') // Đếm tổng sinh viên
            ->latest()
            ->paginate(9);

        return view('teacher.index', compact('classes'));
    }

    // --- 3. CHI TIẾT LỚP HỌC ---
    public function show($id)
    {
        $class = Classroom::with(['registrations.student'])
            ->where('teacher_id', Auth::id()) 
            ->findOrFail($id);

        return view('teacher.show', compact('class'));
    }

    // --- 4. CẬP NHẬT ĐIỂM SỐ ---
    public function updateScore(Request $request, $registration_id)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:10'
        ]);

        $reg = Registration::findOrFail($registration_id);

        // Check quyền: Chỉ giáo viên của lớp đó mới được chấm
        if ($reg->classroom->teacher_id != Auth::id()) {
            abort(403, 'Bạn không có quyền chấm điểm lớp này!');
        }

        $reg->update(['score' => $request->score]);

        // Logic chuyển hướng thông minh
        if ($request->has('redirect_to') && $request->redirect_to == 'students') {
            return redirect()->route('teacher.students')
                             ->with('success', 'Đã chấm điểm cho: ' . ($reg->student->name ?? 'SV'));
        }

        return back()->with('success', 'Đã cập nhật điểm số!');
    }

    // --- 5. DANH SÁCH TẤT CẢ SINH VIÊN (Có tìm kiếm) ---
    public function studentList(Request $request)
    {
        $myClassIds = Classroom::where('teacher_id', Auth::id())->pluck('id');

        $query = Registration::with(['student', 'classroom'])
                             ->whereIn('classroom_id', $myClassIds);

        // Tìm kiếm
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->whereHas('student', function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('code', 'LIKE', "%{$keyword}%");
            });
        }

        // Lọc điểm số
        if ($request->has('filter') && $request->filter == 'graded') {
            $query->whereNotNull('score');
            if ($request->input('sort') == 'desc') {
                $query->orderByDesc('score');
            }
        } else {
            $query->latest();
        }

        $students = $query->paginate(10)->withQueryString();

        return view('teacher.students', compact('students'));
    }
}