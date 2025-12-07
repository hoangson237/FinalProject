<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // <--- Cần cho route reset

// Import các Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminTeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================================================
// 1. ROUTE KHẨN CẤP: XÓA SẠCH SESSION & CACHE
// ==============================================================
// Chạy link này nếu bị lỗi chuyển hướng lung tung: http://127.0.0.1:8000/force-reset
Route::get('/force-reset', function () {
    Auth::logout();             // Đăng xuất
    Session::flush();           // Xóa sạch dữ liệu phiên
    Session::regenerate();      // Tạo ID phiên mới
    return redirect('/login');  // Về trang Login sạch sẽ
});

// ==============================================================
// 2. CẤU HÌNH ĐĂNG NHẬP / ĐĂNG KÝ
// ==============================================================

// Trang chủ: Vào là đá về Login ngay
Route::get('/', function () {
    return redirect()->route('login');
});

// Tắt các route mặc định không dùng, nhưng giữ lại password reset
Auth::routes(['register' => false, 'login' => false, 'logout' => false]); 

// --- GHI ĐÈ ROUTE LOGIN (QUAN TRỌNG) ---
// Route hiển thị form login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route xử lý đăng nhập (Trỏ về hàm login đã sửa trong Controller)
Route::post('login', [LoginController::class, 'login']);

// --- ROUTE LOGOUT ---
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// --- ROUTE ĐĂNG KÝ THỦ CÔNG ---
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


// ==============================================================
// 3. KHU VỰC CHUNG (Đã đăng nhập là vào được)
// ==============================================================
Route::middleware(['auth'])->group(function () {
    // Route Quản lý Profile Cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// ==============================================================
// 4. KHU VỰC ADMIN (Role = 1)
// ==============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Quản lý Lớp học
    Route::get('/classes', [AdminController::class, 'classList'])->name('classes.index');
    Route::get('/class/create', [AdminController::class, 'create'])->name('class.create');
    Route::post('/class/store', [AdminController::class, 'store'])->name('class.store');
    Route::get('/class/edit/{id}', [AdminController::class, 'edit'])->name('class.edit');
    Route::put('/class/update/{id}', [AdminController::class, 'update'])->name('class.update');
    Route::delete('/class/delete/{id}', [AdminController::class, 'destroy'])->name('class.destroy');
    
    // Quản lý Sinh viên (CRUD + Excel)
    Route::get('students/export', [AdminStudentController::class, 'export'])->name('students.export');
    Route::resource('students', AdminStudentController::class);
    
    // Quản lý Giáo viên (CRUD)
    Route::resource('teachers', AdminTeacherController::class);
});


// ==============================================================
// 5. KHU VỰC GIÁO VIÊN (Role = 2)
// ==============================================================
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
   // DASHBOARD (ĐÍCH ĐẾN MONG MUỐN)
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

    // Danh sách lớp dạy
    Route::get('/classes', [TeacherController::class, 'index'])->name('classes');
    
    // Chi tiết sinh viên trong lớp
    Route::get('/class/{id}', [TeacherController::class, 'show'])->name('class.show');

    Route::get('/students', [TeacherController::class, 'studentList'])->name('students');
    
    // Lưu điểm số
    Route::post('/score/{registration_id}', [TeacherController::class, 'updateScore'])->name('score.update');
});


// ==============================================================
// 6. KHU VỰC SINH VIÊN (Role = 0)
// ==============================================================
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // 1. Đăng ký Lớp học
    Route::get('/register-class', [StudentController::class, 'index'])->name('register');
    Route::post('/register-action/{id}', [StudentController::class, 'store'])->name('postRegister');

    // 2. Lớp học của tôi
    Route::get('/my-classes', [StudentController::class, 'myClasses'])->name('myClasses');
    Route::delete('/cancel/{id}', [StudentController::class, 'cancel'])->name('cancel');
});