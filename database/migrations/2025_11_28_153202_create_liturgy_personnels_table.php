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
    Schema::create('liturgy_personnels', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        // Relasi ke tabel lingkungans (nullable karena bisa jadi dari luar)
        $table->foreignId('lingkungan_id')->nullable()->constrained('lingkungans')->onDelete('set null');
        $table->boolean('is_external')->default(false); // Penanda jika luar gereja
        $table->string('external_description')->nullable(); // Nama paroki/asal jika luar
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liturgy_personnels');
    }
};
