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
            // Tambah kolom lingkungan_id (nullable)
            $table->foreignId('lingkungan_id')->nullable()->after('liturgy_personnel_id')->constrained('lingkungans')->onDelete('cascade');
            
            // Ubah liturgy_personnel_id jadi nullable (karena kalau Padus, petugasnya kosong)
            $table->unsignedBigInteger('liturgy_personnel_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('liturgy_assignments', function (Blueprint $table) {
            $table->dropForeign(['lingkungan_id']);
            $table->dropColumn('lingkungan_id');
            // Kembalikan ke tidak nullable (hati-hati jika ada data)
            $table->unsignedBigInteger('liturgy_personnel_id')->nullable(false)->change();
        });
    }
};
