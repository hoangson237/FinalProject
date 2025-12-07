<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // [MAX PING] Thêm dòng này để chuẩn Auth Laravel
            $table->string('password');
            $table->rememberToken(); // Di chuyển lên đây cho đúng chuẩn Laravel (tùy chọn)
            
            // --- Custom Columns ---
            $table->tinyInteger('role')->default(0)->comment('0:SV, 1:Admin, 2:GV');
            $table->string('code', 20)->nullable()->unique();
            
            // Profile Info
            $table->date('birthday')->nullable();
            $table->tinyInteger('gender')->default(1); // Giữ default(1) theo ý bạn
            $table->string('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            
            $table->tinyInteger('status')->default(1);
            
            // Soft Delete & Timestamps
            $table->softDeletes(); 
            $table->timestamps();
        });

        // Các bảng mặc định (Giữ nguyên)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};