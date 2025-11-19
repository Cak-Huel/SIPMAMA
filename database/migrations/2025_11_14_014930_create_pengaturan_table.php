<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            // 'key' akan jadi pengenal unik, misal: 'info_pendaftaran', 'syarat_ketentuan'
            $table->string('key')->unique();
            $table->string('judul');
            $table->longText('isi_teks')->nullable(); // untuk menyimpan teks paragraf yang panjang
            $table->string('file_poster')->nullable();  // untuk menyimpan path poster
            $table->timestamps(); // Membuat `created_at` dan `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
