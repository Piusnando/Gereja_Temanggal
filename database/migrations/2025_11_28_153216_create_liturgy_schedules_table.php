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
    Schema::create('liturgy_schedules', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Misal: Misa Minggu Sore
        $table->dateTime('event_at'); // Tanggal dan Jam
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liturgy_schedules');
    }
};
