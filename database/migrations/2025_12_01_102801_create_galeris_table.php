<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');        // Contoh: "Kegiatan Susenas 2025"
            $table->string('periode');      // Contoh: "Januari - Maret 2025"
            $table->string('foto_path');    // Path file gambar
            $table->text('deskripsi')->nullable(); // Opsional
            $table->timestamps();
        });
    }
};
