<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Thêm SoftDeletes nếu dùng xóa mềm

class Classroom extends Model
{
    use HasFactory, SoftDeletes; // Kích hoạt SoftDeletes
    
    // Khai báo các cột được phép thêm dữ liệu
    protected $fillable = [
        'name', 
        'teacher_id', 
        'max_quantity',     // Sĩ số tối đa (Quota)
        'current_quantity', // Số lượng đã đăng ký
        'status',         // Trạng thái (Mở/Đóng)
        // --- CÁC CỘT MỚI ---
        'start_date',
        'schedule',
        'room',
        // -----------------------
    ];

    // Quan hệ 1: Lớp này do ai dạy? (Nối sang bảng Users)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Quan hệ 2: Lớp này có những đơn đăng ký nào?
    // (Để sau này đếm xem lớp đầy chưa)
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}