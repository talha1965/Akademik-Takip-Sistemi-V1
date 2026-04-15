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
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Hangi dersin ödevi?
        $table->string('title'); // Ödev Başlığı (Örn: SQL Sorgu Alıştırmaları)
        $table->text('description'); // Ödev Detayı
        $table->date('due_date'); // Son Teslim Tarihi
        $table->timestamps();
    });

    // Ödevlerin tamamlanma durumunu her öğrenci için ayrı tutan tablo
    Schema::create('assignment_student', function (Blueprint $table) {
        $table->id();
        $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->boolean('is_completed')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
