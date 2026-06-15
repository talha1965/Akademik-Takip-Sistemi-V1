<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Bölüm bağlantısı — hangi bölümün dersi?
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->nullOnDelete();

            // Dersi veren öğretmen
            $table->foreignId('teacher_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Kredi (AKTS zaten var, bu teorik kredi değeri)
            $table->integer('credits')->default(2); // Örn: 2 kredi

            // Dönem (1. dönem / 2. dönem)
            $table->integer('semester')->default(1); // 1 veya 2

            // Teorik + Pratik ders saatleri (devamsızlık hesabı için)
            $table->integer('theory_hours')->default(2);   // Haftalık teorik saat
            $table->integer('practice_hours')->default(0); // Haftalık pratik saat
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn([
                'department_id',
                'teacher_id',
                'credits',
                'semester',
                'theory_hours',
                'practice_hours',
            ]);
        });
    }
};
