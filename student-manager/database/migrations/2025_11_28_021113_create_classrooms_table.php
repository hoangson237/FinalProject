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
       Schema::create('classrooms', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->unsignedBigInteger('teacher_id')->nullable(); 
        
        // Logic Sĩ số
        $table->integer('max_quantity')->default(40);
        $table->integer('current_quantity')->default(0);
        
        $table->tinyInteger('status')->default(1);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
