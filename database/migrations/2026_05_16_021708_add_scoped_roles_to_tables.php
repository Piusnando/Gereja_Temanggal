<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Tambah kolom di tabel users untuk menyimpan "ikatan" mereka
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('territory_id')->nullable()->after('role')->constrained('territories')->onDelete('set null');
            $table->foreignId('lingkungan_id')->nullable()->after('territory_id')->constrained('lingkungans')->onDelete('set null');
        });

        // 2. Tambah kolom di tabel announcements untuk menandai pemiliknya
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('territory_id')->nullable()->after('category')->constrained('territories')->onDelete('cascade');
            $table->foreignId('lingkungan_id')->nullable()->after('territory_id')->constrained('lingkungans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['territory_id']);
            $table->dropForeign(['lingkungan_id']);
            $table->dropColumn(['territory_id', 'lingkungan_id']);
        });
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['territory_id']);
            $table->dropForeign(['lingkungan_id']);
            $table->dropColumn(['territory_id', 'lingkungan_id']);
        });
    }
};