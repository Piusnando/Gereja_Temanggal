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
        Schema::create('inv_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Elektronik / Sound System
            $table->string('code', 4)->unique(); // Contoh: ELKT (Maksimal 4 huruf)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_categories');
    }
};
