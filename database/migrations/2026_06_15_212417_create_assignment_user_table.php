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
        Schema::create('assignment_user', function (Blueprint $table) {
            $table->id();
            // Ödev Bağlantısı (Ödev silinirse buradaki kayıt da silinsin)
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            // Öğrenci Bağlantısı (Öğrenci silinirse buradaki kayıt da silinsin)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Ödevin tamamlanma durumu (Varsayılan olarak "hayır/false")
            $table->boolean('is_completed')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_user');
    }
};
