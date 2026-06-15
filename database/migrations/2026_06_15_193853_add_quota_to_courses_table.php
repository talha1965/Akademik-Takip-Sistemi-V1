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
        Schema::table('courses', function (Blueprint $table) {
            // Sütun zaten var mı diye kontrol et, yoksa ekle
            if (!Schema::hasColumn('courses', 'quota')) {
                $table->integer('quota')->default(50);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('quota');
        });
    }
};