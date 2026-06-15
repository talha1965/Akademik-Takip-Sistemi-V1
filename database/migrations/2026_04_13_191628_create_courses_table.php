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
    Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique(); // Örn: BIL201
    $table->string('name'); // Örn: Veri Yapıları
    $table->integer('credit'); // Kredi
    $table->integer('akts'); // AKTS
    $table->integer('quota'); // Toplam Kontenjan
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
