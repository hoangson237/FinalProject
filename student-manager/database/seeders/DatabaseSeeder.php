<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classroom;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo ADMIN (Để quản lý hệ thống)
        User::create([
            'name' => 'Thầy Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 1,      // Role 1 = Admin
            'code' => 'ADM01',
            'status' => 1
        ]);

        // 2. Tạo GIÁO VIÊN (Để chấm điểm & đứng lớp)
        // Gán vào biến $gv để tí nữa lấy ID gán cho lớp học
        $gv = User::create([
            'name' => 'Cô Giáo Thảo',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 2,      // Role 2 = Giáo viên
            'code' => 'GV01',
            'status' => 1
        ]);

        // 3. Tạo SINH VIÊN (Để test đăng ký học)
        User::create([
            'name' => 'Em Sinh Viên A',
            'email' => 'student@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 0,      // Role 0 = Sinh viên
            'code' => 'SV01',
            'status' => 1
        ]);

        // 4. Tạo LỚP HỌC MẪU
        
        // Lớp 1: Lớp bình thường (Còn 30 chỗ) -> Để test đăng ký thành công
        Classroom::create([
            'name' => 'Lập trình Laravel - K15',
            'teacher_id' => $gv->id, // Lớp này do cô Thảo dạy
            'max_quantity' => 30,
            'current_quantity' => 0,
            'status' => 1
        ]);

        // Lớp 2: Lớp đã đầy (Max = 0) -> Để test logic báo lỗi "Lớp đã đầy"
        Classroom::create([
            'name' => 'Lập trình Java - K15 (Full)',
            'teacher_id' => $gv->id,
            'max_quantity' => 0, 
            'current_quantity' => 0,
            'status' => 1
        ]);
        
        // In ra màn hình console để bạn nhớ tài khoản
        echo "--------------------------------------\n";
        echo "ĐÃ TẠO XONG DỮ LIỆU MẪU THÀNH CÔNG!\n";
        echo "--------------------------------------\n";
        echo "1. Admin:   admin@gmail.com   / 123456\n";
        echo "2. GV:      teacher@gmail.com / 123456\n";
        echo "3. SV:      student@gmail.com / 123456\n";
        echo "--------------------------------------\n";
    }
}