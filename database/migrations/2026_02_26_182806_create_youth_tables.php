<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Anggota (PIA, PIR, OMK)
        Schema::create('youth_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['PIA', 'PIR', 'OMK']); // Kategori
            $table->foreignId('lingkungan_id')->nullable()->constrained('lingkungans')->onDelete('set null');
            $table->boolean('is_active')->default(true); // Status Aktif/Pasif
            $table->date('birth_date')->nullable(); // Opsional, untuk auto naik kelas
            $table->string('phone')->nullable();
            $table->timestamps();
        });



        // 3. Tabel Presensi (Mencatat Siapa Hadir Dimana)
        Schema::create('youth_attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('youth_member_id')->constrained('youth_members')->onDelete('cascade');
        
        // PASTIKAN INI ADALAH activity_id (BUKAN youth_event_id)
        $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
        
        $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_attendances');
        Schema::dropIfExists('youth_events');
        Schema::dropIfExists('youth_members');
    }
};