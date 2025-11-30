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
            $table->string('password');
            
            // Các cột custom
            $table->tinyInteger('role')->default(0)->comment('0:SV, 1:Admin, 2:GV');
            $table->string('code', 20)->nullable()->unique();
            $table->date('birthday')->nullable();
            // Lưu ý: DBML ghi gender cho phép null, ở đây bạn để default(1). 
            // Tôi giữ nguyên theo code của bạn vì nó hợp lý hơn.
            $table->tinyInteger('gender')->default(1);
            $table->string('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->tinyInteger('status')->default(1);
            
            // Soft Delete & Timestamps (Đã chuẩn)
            $table->softDeletes(); 
            $table->rememberToken();
            $table->timestamps();
        });

        // Các bảng mặc định cần thiết của Laravel
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