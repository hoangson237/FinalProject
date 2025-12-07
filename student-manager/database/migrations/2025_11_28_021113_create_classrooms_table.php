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

            // [MAX PING] Teacher: Thêm onDelete cascade để khi xóa GV thì lớp cũng bay (hoặc set null tùy logic)
            // Ở đây tôi giữ nullable theo ý bạn, nhưng thêm ràng buộc xóa.
            $table->foreignId('teacher_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade'); 
            
            // Logic Sĩ số (Chuẩn DBML)
            $table->integer('max_quantity')->default(20);
            $table->integer('current_quantity')->default(0);
            
            $table->tinyInteger('status')->default(1);
            
            // [MAX PING] Thêm SoftDeletes cho lớp học (Phòng khi xóa nhầm lớp)
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};