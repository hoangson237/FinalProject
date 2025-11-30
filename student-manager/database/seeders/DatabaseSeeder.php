<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classroom;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // MẬT KHẨU CHUNG CHO TẤT CẢ LÀ: 12345678 (8 số)
        $password = Hash::make('12345678'); 

        // 1. Tạo ADMIN
        User::create([
            'name' => 'Thầy Admin',
            'email' => 'admin@gmail.com',
            'password' => $password, 
            'role' => 1,
            'code' => 'ADM01',
            'status' => 1
        ]);

        // 2. Tạo GIÁO VIÊN
        $gv = User::create([
            'name' => 'Cô Giáo Thảo',
            'email' => 'teacher@gmail.com',
            'password' => $password,
            'role' => 2,
            'code' => 'GV01',
            'status' => 1
        ]);

        // 3. Tạo SINH VIÊN
        User::create([
            'name' => 'Em Sinh Viên A',
            'email' => 'student@gmail.com',
            'password' => $password,
            'role' => 0,
            'code' => 'SV01',
            'status' => 1
        ]);

        // 4. Tạo LỚP HỌC MẪU
        // Lớp 1: Còn chỗ
        Classroom::create([
            'name' => 'Lập trình Laravel - K15',
            'teacher_id' => $gv->id,
            'max_quantity' => 30,
            'current_quantity' => 0,
            'status' => 1
        ]);

        // Lớp 2: Đã đầy (Max = 0)
        Classroom::create([
            'name' => 'Lập trình Java - K15 (Full)',
            'teacher_id' => $gv->id,
            'max_quantity' => 0, 
            'current_quantity' => 0,
            'status' => 1
        ]);
        
        echo "--------------------------------------\n";
        echo "ĐÃ RESET DỮ LIỆU THÀNH CÔNG!\n";
        echo "Mật khẩu chung cho tất cả: 12345678\n";
        echo "--------------------------------------\n";
    }
}