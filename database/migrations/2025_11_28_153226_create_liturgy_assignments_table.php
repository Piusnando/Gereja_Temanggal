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
    Schema::create('liturgy_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('liturgy_schedule_id')->constrained()->onDelete('cascade');
        $table->foreignId('liturgy_personnel_id')->constrained()->onDelete('cascade');
        $table->string('role'); // Misdinar, Lektor, Mazmur, dll
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liturgy_assignments');
    }
};
