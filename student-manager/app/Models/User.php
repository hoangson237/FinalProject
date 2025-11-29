<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Những trường được phép lưu vào Database
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        
        // --- QUAN TRỌNG: Các cột mới thêm ---
        'role',      // 0:SV, 1:Admin, 2:GV
        'code',      // Mã định danh
        'birthday',  // Ngày sinh
        'gender',    // Giới tính
        'phone',     // SĐT
        'address',   // Địa chỉ
        'avatar',    // Ảnh đại diện
        'status',    // Trạng thái
    ];

    /**
     * Những trường cần giấu đi khi trả về JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- CÁC HÀM HỖ TRỢ KIỂM TRA QUYỀN (Helper) ---
    // Giúp code trong Controller gọn hơn: if ($user->isAdmin()) ...
    
    public function isAdmin() {
        return $this->role == 1;
    }

    public function isTeacher() {
        return $this->role == 2;
    }

    public function isStudent() {
        return $this->role == 0;
    }

    // --- KHAI BÁO QUAN HỆ (RELATIONSHIPS) ---
    
    // 1. Nếu là Giáo viên -> Có nhiều lớp dạy
    public function classesTeaching() {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    // 2. Nếu là Sinh viên -> Có nhiều bản đăng ký học
    public function registrations() {
        return $this->hasMany(Registration::class, 'student_id');
    }
}