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
    Schema::table('course_user', function (Blueprint $table) {
        // Öğrencinin yaptığı devamsızlık sayısı
        $table->integer('absences_count')->default(0); 
        // Öğrencinin o ders için hocasından duyup belirlediği sınır (Varsayılan: 4)
        $table->integer('student_limit')->default(4); 
    });
}

public function down(): void
{
    Schema::table('course_user', function (Blueprint $table) {
        $table->dropColumn(['absences_count', 'student_limit']);
    });
}   
};
