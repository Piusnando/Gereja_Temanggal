<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Kolom untuk menandai tipe kegiatan
            // 'general' = Berita Kegiatan Umum
            // 'youth' = Kegiatan Bina Iman
            $table->string('type')->default('general')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};