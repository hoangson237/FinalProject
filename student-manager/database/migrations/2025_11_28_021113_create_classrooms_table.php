<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('classrooms', function (Blueprint $table) {
        $table->id();
        $table->string('name');

        // --- PHẦN ĐÃ SỬA ---
        // Cũ: $table->unsignedBigInteger('teacher_id')->nullable();
        // Mới (Chuẩn Pro): Dùng foreignId và constrained để tạo khóa ngoại thực sự.
        // Tôi giữ lại nullable() để cho phép tạo lớp trước khi gán GV.
        $table->foreignId('teacher_id')->nullable()->constrained('users'); // <--- ĐÃ SỬA Ở ĐÂY (Thêm ràng buộc)
        
        // Logic Sĩ số
        // Cũ: $table->integer('max_quantity')->default(40);
        // Mới: Sửa về 20 cho khớp DBML
        $table->integer('max_quantity')->default(20); // <--- ĐÃ SỬA Ở ĐÂY (Về 20 theo DBML)
        $table->integer('current_quantity')->default(0);
        
        $table->tinyInteger('status')->default(1);
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};