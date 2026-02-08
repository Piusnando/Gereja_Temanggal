<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah Foto Santo di Tabel Lingkungan
        Schema::table('lingkungans', function (Blueprint $table) {
            $table->string('saint_image')->nullable()->after('patron_saint'); 
        });

        // 2. Tambah Relasi di Tabel Activities
        Schema::table('activities', function (Blueprint $table) {
            // Jika NULL = Kegiatan Paroki (Semua Lingkungan)
            // Jika Terisi = Kegiatan Spesifik Lingkungan
            $table->foreignId('lingkungan_id')->nullable()->after('organizer')->constrained('lingkungans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('lingkungans', function (Blueprint $table) {
            $table->dropColumn('saint_image');
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['lingkungan_id']);
            $table->dropColumn('lingkungan_id');
        });
    }
};