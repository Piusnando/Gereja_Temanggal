<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Anggota OMK & PIA/PIR
        Schema::create('youth_members', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 'OMK' atau 'PIA/PIR'
            $table->string('name');
            $table->string('baptism_name')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('territory_id')->nullable()->constrained('territories')->onDelete('set null');
            $table->foreignId('lingkungan_id')->nullable()->constrained('lingkungans')->onDelete('set null');
            $table->timestamps();
        });

        // Tabel Jadwal Kegiatan Khusus
        Schema::create('youth_events', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 'OMK' atau 'PIA/PIR'
            $table->string('title');
            $table->dateTime('event_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tabel Absensi
        Schema::create('youth_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youth_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('youth_member_id')->constrained()->onDelete('cascade');
            $table->enum('status',['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Alpa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('youth_attendances');
        Schema::dropIfExists('youth_events');
        Schema::dropIfExists('youth_members');
    }
};