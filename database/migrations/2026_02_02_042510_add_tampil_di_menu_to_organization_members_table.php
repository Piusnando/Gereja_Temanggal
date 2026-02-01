<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_members', function (Blueprint $table) {
            // Kolom baru untuk kontrol visibilitas di menu
            // Defaultnya false (tidak tampil)
            $table->boolean('tampil_di_menu')->default(false)->after('sub_bidang');
        });
    }

    public function down(): void
    {
        Schema::table('organization_members', function (Blueprint $table) {
            $table->dropColumn('tampil_di_menu');
        });
    }
};