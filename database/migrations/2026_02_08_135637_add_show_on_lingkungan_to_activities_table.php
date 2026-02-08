<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Kolom baru untuk kontrol tampilan
            // Default TRUE agar data lama tetap tampil
            $table->boolean('show_on_lingkungan_page')->default(true)->after('lingkungan_id');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('show_on_lingkungan_page');
        });
    }
};