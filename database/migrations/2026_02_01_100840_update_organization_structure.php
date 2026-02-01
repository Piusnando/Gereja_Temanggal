<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_members', function (Blueprint $table) {
            // 1. Tambah kolom baru
            $table->string('bidang')->nullable()->after('id');      // Contoh: Tim Pelayanan Bidang Liturgi
            $table->string('sub_bidang')->nullable()->after('bidang'); // Contoh: Prodiakon
            
            // 2. Hapus kolom lama (category)
            // Note: Data lama akan hilang jika tidak di-backup. 
            // Jika ini masih tahap dev, hapus saja tidak apa-apa.
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('organization_members', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->dropColumn(['bidang', 'sub_bidang']);
        });
    }
};