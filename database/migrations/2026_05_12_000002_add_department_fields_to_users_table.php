<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rol sistemi (zaten add_role_to_users_table var, bu satırı o migration'a göre atla)
            // $table->string('role')->default('student'); // Eğer henüz yoksa aç

            // Bölüm bağlantısı
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->nullOnDelete();

            // Öğrenci bilgileri
            $table->string('student_number')->nullable()->unique(); // Örn: 2023123456

            // Öğretmen bilgileri
            $table->string('title')->nullable();        // Örn: Dr. Öğr. Üyesi
            $table->string('office_room')->nullable();  // Örn: B-204
            $table->string('office_hours')->nullable(); // Örn: Salı 10:00-12:00
            $table->string('institution_email')->nullable(); // Örn: ahmet@ogu.edu.tr
            $table->text('biography')->nullable();      // Kısa akademik biyografi
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn([
                'department_id',
                'student_number',
                'title',
                'office_room',
                'office_hours',
                'institution_email',
                'biography',
            ]);
        });
    }
};
