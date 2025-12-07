<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Registration;

class AdminController extends Controller
{
    // --- MODULE 1: DASHBOARD (OVERVIEW) ---
    public function index()
    {
        // 1. Stats for dashboard cards
        $stats = [
            'students' => User::where('role', 0)->count(),
            'teachers' => User::where('role', 2)->count(),
            'classes'  => Classroom::count(),
        ];

        // 2. Get 5 latest registrations
        $recent_registrations = Registration::with(['student', 'classroom'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact('stats', 'recent_registrations'));
    }

    // --- MODULE 2: CLASSROOM MANAGEMENT (CRUD) ---

    // List Classes (with Filtering)
    public function classList(Request $request)
    {
        $query = Classroom::with('teacher');

        // Filter by Keyword
        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('name', 'LIKE', "%{$request->keyword}%");
        }

        // Filter by Teacher
        if ($request->has('teacher_id') && $request->teacher_id != '') {
            $query->where('teacher_id', $request->teacher_id);
        }

        // 🔥 FIX: SMART STATUS FILTER LOGIC 🔥
        if ($request->has('status') && $request->status != '') {
            
            if ($request->status == '1') {
                // FILTER: "OPEN"
                // Condition: Status is 1 AND Current Quantity < Max Quantity
                $query->where('status', 1)
                      ->whereColumn('current_quantity', '<', 'max_quantity');
            } 
            elseif ($request->status == '0') {
                // FILTER: "CLOSED"
                // Condition: Status is 0 OR Current Quantity >= Max Quantity (Full)
                $query->where(function($q) {
                    $q->where('status', 0)
                      ->orWhereColumn('current_quantity', '>=', 'max_quantity');
                });
            }
        }

        $classrooms = $query->latest()->paginate(10)->withQueryString();
        $teachers = User::where('role', 2)->get();

        return view('admin.classes.index', compact('classrooms', 'teachers'));
    }

    // Show Create Form
    public function create()
    {
        $teachers = User::where('role', 2)->get(); 
        return view('admin.create', compact('teachers')); // Ensure this view exists, usually admin.classes.create
    }

    // Store New Class
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

        return redirect()->route('admin.classes.index')->with('success', 'Đã tạo lớp học thành công!');
    }

    // Show Edit Form
    public function edit($id)
    {
        $class = Classroom::findOrFail($id);
        $teachers = User::where('role', 2)->get();
        // Assuming your edit view is located at admin.classes.edit based on previous context
        return view('admin.classes.edit', compact('class', 'teachers')); 
    }

    // Update Class
    public function update(Request $request, $id)
    {
        $class = Classroom::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:users,id',
            'max_quantity' => 'required|integer|min:1|max:20',
            'status' => 'required',
        ], [
            'max_quantity.max' => 'Sĩ số lớp không được vượt quá 20 người!'
        ]);

        // 🔥 LOGIC CHECK: PREVENT OPENING FULL CLASSES 🔥
        $newMaxQty = $request->max_quantity;
        $currentQty = $class->current_quantity;
        $newStatus = $request->status;

        // If trying to set status to OPEN (1) but class is FULL
        if ($newStatus == 1 && $currentQty >= $newMaxQty) {
            // Force status to CLOSED (0) or redirect back with error
            // Here we force close it to match the UI behavior
            $newStatus = 0; 
            
            // Optional: You could redirect back with an error instead:
            // return back()->withErrors(['status' => 'Không thể mở lớp vì sĩ số đã đầy. Vui lòng tăng sĩ số tối đa.']);
        }

        $class->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'max_quantity' => $newMaxQty,
            'status' => $newStatus, 
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Cập nhật lớp học thành công!');
    }

    // Delete Class
    public function destroy($id)
    {
        $class = Classroom::findOrFail($id);
        $class->delete();
        return back()->with('success', 'Đã xóa lớp học!');
    }
}