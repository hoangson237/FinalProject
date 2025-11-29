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
        // 1. Bảng USERS (Đã có)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Các cột custom của chúng ta
            $table->tinyInteger('role')->default(0)->comment('0:SV, 1:Admin, 2:GV');
            $table->string('code', 20)->nullable()->unique();
            $table->date('birthday')->nullable();
            $table->tinyInteger('gender')->default(1);
            $table->string('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->tinyInteger('status')->default(1);
            
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Bảng PASSWORD RESET TOKENS (Bắt buộc để Reset Pass)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Bảng SESSIONS (Đây là bảng bạn đang bị thiếu!)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};