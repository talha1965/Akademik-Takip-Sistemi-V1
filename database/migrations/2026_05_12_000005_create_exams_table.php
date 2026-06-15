<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->enum('exam_type', ['vize', 'final', 'butunleme'])->default('vize');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('classroom');      // Örn: A-101, Amfi 2
            $table->text('notes')->nullable(); // Öğrencilere not
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
