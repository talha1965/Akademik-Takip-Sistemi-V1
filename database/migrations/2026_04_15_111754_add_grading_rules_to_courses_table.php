<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Ağırlıklar (Toplamları 100 olmalı - varsayılan %40 vize, %60 final)
            $table->integer('vize_weight')->default(40);
            $table->integer('final_weight')->default(60);
            $table->integer('proje_weight')->default(0); // İsteğe bağlı
            
            // Minimum geçme notu (CC barajı)
            $table->integer('passing_grade')->default(50);
            
            // Harf notu sınırlarını tutacak JSON yapısı (İleride hocalar değiştirebilsin diye)
            $table->json('grading_scale')->nullable();
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['vize_weight', 'final_weight', 'proje_weight', 'passing_grade', 'grading_scale']);
        });
    }
};