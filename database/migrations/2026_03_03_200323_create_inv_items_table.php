<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inv_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Barang (Misal: Mic Wireless Shure)
            $table->foreignId('inv_location_id')->constrained('inv_locations')->onDelete('restrict');
            $table->foreignId('inv_category_id')->constrained('inv_categories')->onDelete('restrict');
            $table->string('serial_number'); // Nomor Seri (Misal: 001)
            $table->string('item_code')->unique(); // Kode Full (Misal: GSTI_GRJA_ELKT_001)
            $table->enum('condition', ['Baik', 'Rusak Sedang', 'Rusak Berat'])->default('Baik');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_items');
    }
};
