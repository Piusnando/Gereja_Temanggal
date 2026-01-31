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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->string('organizer'); // Penyelenggara (OMK, PIA, Wilayah, dll)
            $table->dateTime('start_time'); // Tanggal & Jam Mulai
            $table->dateTime('end_time')->nullable(); // Tanggal & Jam Selesai
            $table->string('location')->default('Gereja');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
