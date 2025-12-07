<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use App\Models\Registration;

class TeacherController extends Controller
{
    // ... (Các hàm dashboard, index, show giữ nguyên) ...

    public function dashboard()
    {
        $teacher_id = Auth::id();
        $myClassIds = Classroom::where('teacher_id', $teacher_id)->pluck('id');

        $stats = [
            'count_classes' => $myClassIds->count(),
            'count_students' => Registration::whereIn('classroom_id', $myClassIds)->whereHas('student')->count(),
            'count_graded' => Registration::whereIn('classroom_id', $myClassIds)->whereHas('student')->whereNotNull('score')->count(),
        ];

        $recent_activities = Registration::with(['student', 'classroom'])
            ->whereIn('classroom_id', $myClassIds)
            ->whereHas('student')
            ->latest()
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact('stats', 'recent_activities'));
    }

    public function index()
    {
        $classes = Classroom::where('teacher_id', Auth::id())
            ->withCount(['registrations' => function($query) {
                $query->whereHas('student');
            }])
            ->latest()
            ->paginate(9);

        return view('teacher.index', compact('classes'));
    }

    public function show($id)
    {
        $class = Classroom::with(['registrations' => function($query) {
                $query->whereHas('student');
            }, 'registrations.student'])
            ->where('teacher_id', Auth::id()) 
            ->findOrFail($id);

        return view('teacher.show', compact('class'));
    }

    // --- CẬP NHẬT ĐIỂM SỐ (QUAN TRỌNG: Xử lý Highlight) ---
    public function updateScore(Request $request, $registration_id)
    {
        $request->validate([
            'score' => 'nullable|numeric|min:0|max:10' // Cho phép null để "Chấm lại"
        ]);

        $reg = Registration::findOrFail($registration_id);

        if ($reg->classroom->teacher_id != Auth::id()) {
            abort(403);
        }

        // Cập nhật điểm (nếu input score = 0 và logic là hủy thì set null, tùy logic bạn)
        // Ở đây mình giữ nguyên logic update giá trị nhận được
        $reg->update(['score' => $request->score]);

        // --- XỬ LÝ CHUYỂN HƯỚNG & HIGHLIGHT ---
        
        // Tạo Fragment (dấu thăng #reg-123) để frontend biết dòng nào cần tô màu
        $fragment = 'reg-' . $reg->id;

        // Nếu yêu cầu chuyển về trang danh sách (từ input hidden redirect_to)
        if ($request->input('redirect_to') == 'students') {
            return redirect()->route('teacher.students', [
                'filter' => $request->score !== null ? 'graded' : null // Nếu có điểm thì về tab graded, không thì về chờ chấm
            ])
            ->with('success', 'Đã lưu điểm!')
            ->withFragment($fragment); // <--- QUAN TRỌNG: Gắn đuôi #reg-ID
        }

        return back()->with('success', 'Đã cập nhật điểm!')->withFragment($fragment);
    }

    // --- DANH SÁCH SINH VIÊN (Xử lý Sắp xếp) ---
  public function studentList(Request $request)
    {
        $myClassIds = Classroom::where('teacher_id', Auth::id())->pluck('id');
        $query = Registration::with(['student', 'classroom'])
                             ->whereIn('classroom_id', $myClassIds)
                             ->whereHas('student'); 

        // 1. Tab Đã Chấm (Bảng Vàng)
        if ($request->has('filter') && $request->filter == 'graded') {
            $query->whereNotNull('score');

            // NÚT SORT: Chỉ xếp khi có request
            if ($request->has('sort') && $request->sort == 'desc') {
                $query->orderByDesc('score');
            } else {
                $query->latest('updated_at'); // Mặc định không xếp hạng
            }
        } else {
            // 2. Tab Chờ Chấm
            $query->latest(); 
        }

        $students = $query->paginate(20)->withQueryString();
        return view('teacher.students', compact('students'));
    }
}