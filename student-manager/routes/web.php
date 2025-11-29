<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminTeacherController; // <--- Cần thiết


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// --- KHU VỰC ADMIN (Role = 1) ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
   // 1. Route Tổng quan
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // 2. Route Quản lý Lớp học (Mới)
    Route::get('/classes', [AdminController::class, 'classList'])->name('classes.index');
    
    // --- THÊM 2 DÒNG NÀY ---
    Route::get('/class/create', [AdminController::class, 'create'])->name('class.create'); // Form
    Route::post('/class/store', [AdminController::class, 'store'])->name('class.store');   // Lưu
    Route::get('/class/edit/{id}', [AdminController::class, 'edit'])->name('class.edit');     // Form Sửa
    Route::put('/class/update/{id}', [AdminController::class, 'update'])->name('class.update'); // Hành động Update
    Route::delete('/class/delete/{id}', [AdminController::class, 'destroy'])->name('class.destroy'); // Hành động Xóa
    // CRUD SINH VIÊN (Thêm dòng này)
    Route::resource('students', AdminStudentController::class);
    // 3. CRUD GIÁO VIÊN (Teacher Management) <--- DÒNG BẠN CẦN CHẮC CHẮN CÓ
    Route::resource('teachers', AdminTeacherController::class);
    
    // 4. ROUTE EXCEL (Advanced)
    Route::get('students/export', [AdminStudentController::class, 'export'])->name('students.export');
});

// --- KHU VỰC GIÁO VIÊN (Role = 2) ---
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    
    // Danh sách lớp dạy
    Route::get('/classes', [TeacherController::class, 'index'])->name('classes');
    
    // Chi tiết sinh viên trong lớp
    Route::get('/class/{id}', [TeacherController::class, 'show'])->name('class.show');
    
    // Lưu điểm số (POST)
    Route::post('/score/{registration_id}', [TeacherController::class, 'updateScore'])->name('score.update');
});

// --- KHU VỰC SINH VIÊN (Role = 0) ---
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    
    // Xem danh sách
    Route::get('/register-class', [StudentController::class, 'index'])->name('register');
    
    // Hành động bấm nút đăng ký (Thêm dòng này)
    Route::post('/register-action/{id}', [StudentController::class, 'store'])->name('postRegister');

});