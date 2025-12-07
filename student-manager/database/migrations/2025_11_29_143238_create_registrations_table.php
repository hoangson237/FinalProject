<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            
            // [MAX PING] Điểm số: Dùng decimal thay vì float để tránh lỗi làm tròn
            // 4 chữ số tổng, 2 số sau dấu phẩy (VD: 10.00)
            $table->decimal('score', 4, 2)->nullable(); 
            
            // Chống trùng lặp
            $table->unique(['student_id', 'classroom_id']); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};