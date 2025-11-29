<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    // Khai báo các cột được phép thêm dữ liệu
    protected $fillable = [
        'student_id', 
        'classroom_id', 
        'score' // Quan trọng: Để sau này Giáo viên nhập điểm
    ];

    // Mối quan hệ 1: Bản đăng ký này của Sinh viên nào?
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Mối quan hệ 2: Đăng ký vào Lớp nào?
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}