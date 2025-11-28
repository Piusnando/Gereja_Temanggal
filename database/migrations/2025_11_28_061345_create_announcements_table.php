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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content'); // Isi ringkas
            $table->string('image_path')->nullable(); // Foto pengumuman
            $table->string('category')->default('Umum'); // Misal: Liturgi, Kegiatan, dll
            $table->date('event_date')->nullable(); // Tanggal acara/pengumuman
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
