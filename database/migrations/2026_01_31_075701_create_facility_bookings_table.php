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
        Schema::create('facility_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('facility_name'); // Contoh: Gereja, Aula, Ruang Rapat
            $table->string('booked_by');     // Contoh: Lingkungan A, OMK
            $table->string('purpose');       // Contoh: Latihan Tablo, Misa Wilayah
            $table->dateTime('start_time');  // Mulai
            $table->dateTime('end_time');    // Selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_bookings');
    }
};
