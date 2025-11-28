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
        Schema::create('lingkungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('territory_id')->constrained()->onDelete('cascade'); // Relasi ke Wilayah
            $table->string('name'); // Nama Lingkungan
            $table->string('patron_saint')->nullable(); // Nama Pelindung (opsional)
            $table->string('chief_name')->nullable(); // Nama Ketua Lingkungan (opsional)
            $table->text('info')->nullable(); // Info tambahan (jadwal doa, lokasi, dll)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lingkungans');
    }
};
