<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke data pendaftar (bukan user langsung, biar terikat periode)
            $table->foreignId('pendaftar_id')->constrained('pendaftar', 'id_pendaftar')->onDelete('cascade');

            $table->date('tanggal');
            $table->time('jam');

            // Jenis: Datang, Pulang, Izin, Sakit
            $table->string('jenis');

            // Data Lokasi
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // Data Izin/Sakit
            $table->text('keterangan')->nullable(); // Untuk Izin
            $table->string('bukti_file')->nullable(); // Untuk Izin/Sakit (Path File)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
