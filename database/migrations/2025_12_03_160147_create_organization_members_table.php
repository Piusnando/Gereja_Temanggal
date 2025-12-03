<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // OMK, KOMSOS, Pengurus Gereja, dll
            $table->string('name');
            $table->string('position'); // Jabatan (Ketua, Anggota, dll)
            $table->foreignId('lingkungan_id')->nullable()->constrained('lingkungans')->onDelete('set null');
            $table->string('image')->nullable(); // Foto (Opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
