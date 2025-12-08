<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// Import đầy đủ các Controller
use App\Http\Controllers\AdminController;       // Controller Admin (Chứa cả Dashboard và CRUD Lớp)
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
Route::get('/force-reset', function () {
    Auth::logout();
    Session::flush();
    Session::regenerate();
    return redirect('/login');
});

// ==============================================================
// 2. CẤU HÌNH ĐĂNG NHẬP / ĐĂNG KÝ
// ==============================================================

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false, 'login' => false, 'logout' => false]); 

// Login & Logout
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Đăng ký thủ công
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


// ==============================================================
// 3. KHU VỰC CHUNG (Đã đăng nhập là vào được)
// ==============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// ==============================================================
// 4. KHU VỰC ADMIN (Role = 1)
// ==============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Quản lý Lớp học (Vẫn dùng AdminController như bạn muốn)
    Route::get('/classes', [AdminController::class, 'classList'])->name('classes.index');
    
    // --- QUẢN LÝ THÙNG RÁC LỚP HỌC ---
    Route::get('/classes/trash', [AdminController::class, 'trash'])->name('classes.trash'); // Xem thùng rác
    Route::get('/classes/restore/{id}', [AdminController::class, 'restore'])->name('classes.restore'); // Khôi phục
    Route::delete('/classes/force-delete/{id}', [AdminController::class, 'forceDelete'])->name('classes.forceDelete'); // Xóa vĩnh viễn

    Route::get('/class/create', [AdminController::class, 'create'])->name('class.create');
    Route::post('/class/store', [AdminController::class, 'store'])->name('class.store');
    
    Route::get('/class/edit/{id}', [AdminController::class, 'edit'])->name('class.edit');
    Route::put('/class/update/{id}', [AdminController::class, 'update'])->name('class.update');
    
    Route::delete('/class/delete/{id}', [AdminController::class, 'destroy'])->name('class.destroy'); // Xóa mềm
    
    // Quản lý Sinh viên & Giáo viên
    Route::get('students/export', [AdminStudentController::class, 'export'])->name('students.export');
    Route::resource('students', AdminStudentController::class);
    Route::resource('teachers', AdminTeacherController::class);
});


// ==============================================================
// 5. KHU VỰC GIÁO VIÊN (Role = 2)
// ==============================================================
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/classes', [TeacherController::class, 'index'])->name('classes');
    Route::get('/class/{id}', [TeacherController::class, 'show'])->name('class.show');
    Route::get('/students', [TeacherController::class, 'studentList'])->name('students');
    Route::post('/score/{registration_id}', [TeacherController::class, 'updateScore'])->name('score.update');
});


// ==============================================================
// 6. KHU VỰC SINH VIÊN (Role = 0)
// ==============================================================
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Đăng ký Lớp học
    Route::get('/register-class', [StudentController::class, 'index'])->name('register');
    Route::post('/register-action/{id}', [StudentController::class, 'store'])->name('postRegister');

    // Lớp học của tôi
    Route::get('/my-classes', [StudentController::class, 'myClasses'])->name('myClasses');
    
    // Hủy đăng ký (Quan trọng cho logic mới)
    Route::delete('/cancel/{id}', [StudentController::class, 'cancel'])->name('cancel');
});