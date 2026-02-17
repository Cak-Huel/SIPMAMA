<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kuota_periodes', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan'); // 1 - 12
            $table->integer('tahun'); // 2025
            $table->integer('kuota'); // Angka khusus (misal: 15)
            $table->timestamps();

            // Mencegah duplikasi (Satu bulan hanya punya satu setting)
            $table->unique(['bulan', 'tahun']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kuota_periodes');
    }
};
