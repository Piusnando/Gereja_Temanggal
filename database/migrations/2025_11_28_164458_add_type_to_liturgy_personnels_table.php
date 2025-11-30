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
    Schema::table('liturgy_personnels', function (Blueprint $table) {
        // Kolom untuk menyimpan jenis petugas (Misdinar, Lektor, dll)
        $table->string('type')->default('Umum')->after('name'); 
    });
}

public function down()
{
    Schema::table('liturgy_personnels', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
