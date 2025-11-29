<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_code', 20)->unique(); // Mã SV
            $table->string('name');
            $table->date('birthday');
            $table->tinyInteger('gender')->default(1)->comment('1: Nam, 0: Nữ');
            $table->string('phone', 15)->nullable();
            $table->string('email')->nullable(); // Email nhận thông báo
            $table->string('avatar')->nullable(); // Ảnh đại diện
            
            // Khóa ngoại: Liên kết sang bảng classrooms
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            
            $table->softDeletes(); // Cho phép xóa mềm (thùng rác)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
