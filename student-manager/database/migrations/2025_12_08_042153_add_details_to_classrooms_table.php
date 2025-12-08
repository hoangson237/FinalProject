<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('classrooms', function (Blueprint $table) {
        // Thêm 3 cột mới (cho phép null để không lỗi dữ liệu cũ)
        $table->date('start_date')->nullable()->after('status');    // Ngày bắt đầu
        $table->string('schedule')->nullable()->after('start_date'); // Lịch học (T2-4-6)
        $table->string('room')->nullable()->after('schedule');       // Phòng học (A201)
    });
}

public function down()
{
    Schema::table('classrooms', function (Blueprint $table) {
        // Nếu rollback (quay lại) thì xóa 3 cột này đi
        $table->dropColumn(['start_date', 'schedule', 'room']);
    });
}
};
