<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Registration;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MẬT KHẨU CHUNG: 12345678
        $password = Hash::make('12345678'); 

        // 2. TẠO ADMIN (Để test quyền quản trị)
        User::create([
            'name' => 'Thầy Admin',
            'email' => 'admin@gmail.com',
            'password' => $password, 
            'role' => 1, 
            'code' => 'ADM01', 
            'status' => 1,
            'address' => 'Hà Nội',
            'phone' => '0988888888'
        ]);

        // 3. TẠO GIÁO VIÊN (Cô Giáo Thảo)
        $gv = User::create([
            'name' => 'Cô Giáo Thảo',
            'email' => 'teacher@gmail.com',
            'password' => $password, 
            'role' => 2, 
            'code' => 'GV01', 
            'status' => 1,
            'address' => 'TP.HCM',
            'phone' => '0912345678'
        ]);

        // 4. TẠO SINH VIÊN MẪU (Để bạn đăng nhập test)
        User::create([
            'name' => 'Em Sinh Viên A',
            'email' => 'student@gmail.com',
            'password' => $password, 
            'role' => 0, 
            'code' => 'SV01', 
            'status' => 1,
            'address' => 'Đà Nẵng'
        ]);

        // ====================================================
        // 5. TẠO LỚP 1: LARAVEL (Max 20, Đã có 5 người)
        // ====================================================
        $class1 = Classroom::create([
            'name' => 'Lập trình Laravel - K15',
            'teacher_id' => $gv->id,
            'max_quantity' => 20,    // Chuẩn DBML
            'current_quantity' => 5, // Khớp với 5 SV bên dưới
            'status' => 1
        ]);

        // ---> Vòng lặp tạo 5 sinh viên thật chui vào lớp này
        for ($i = 1; $i <= 5; $i++) {
            $student = User::create([
                'name' => "Sinh viên Laravel $i",
                'email' => "laravel_$i@gmail.com",
                'password' => $password, 
                'role' => 0, 
                'code' => "SVL$i", 
                'status' => 1,
                'phone' => "090000000$i"
            ]);

            // Đăng ký vào lớp
            Registration::create([
                'student_id' => $student->id,   // Dùng đúng student_id
                'classroom_id' => $class1->id,
                'score' => 8.50,                // Điểm số (Decimal nhận tốt)
            ]);
        }

        // ====================================================
        // 6. TẠO LỚP 2: JAVA (Max 20, Đã có 20 người -> FULL)
        // ====================================================
        $class2 = Classroom::create([
            'name' => 'Lập trình Java - K15 (Full)',
            'teacher_id' => $gv->id,
            'max_quantity' => 20,
            'current_quantity' => 20, // Full
            'status' => 1
        ]);

        // ---> Vòng lặp tạo 20 sinh viên thật lấp đầy lớp này
        // (Dữ liệu nhiều giúp bạn test chức năng PHÂN TRANG)
        for ($i = 1; $i <= 20; $i++) {
            $student = User::create([
                'name' => "Sinh viên Java $i",
                'email' => "java_$i@gmail.com",
                'password' => $password, 
                'role' => 0, 
                'code' => "SVJ$i", 
                'status' => 1
            ]);

            Registration::create([
                'student_id' => $student->id,
                'classroom_id' => $class2->id,
                'score' => null, // Chưa có điểm
            ]);
        }
        
        echo "--------------------------------------\n";
        echo "DA RESET DB THANH CONG (HOP LY VOI DO AN)!\n";
        echo "San sang demo CRUD, Phan trang, Upload anh.\n";
        echo "--------------------------------------\n";
    }
}