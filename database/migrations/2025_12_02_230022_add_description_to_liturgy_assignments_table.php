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
        Schema::table('liturgy_assignments', function (Blueprint $table) {
            // Menambahkan kolom description yang boleh kosong (nullable)
            // Letakkan setelah kolom 'liturgy_personnel_id' agar rapi
            $table->string('description')->nullable()->after('liturgy_personnel_id');
        });
    }

    public function down()
    {
        Schema::table('liturgy_assignments', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
