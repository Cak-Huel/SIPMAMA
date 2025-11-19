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
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id('id_pendaftar');

            // untuk menghubungkan tabel ini ke tabel 'users'
            // kolom 'user_id'
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');

            $table->string('nama_lengkap');

            $table->string('nim', 20)->unique(); // batas 20 karakter dan harus unik
            $table->string('universitas');
            $table->date('tgl_start');
            $table->date('tgl_end');
            $table->string('wa', 20); // Nomor WA
            $table->string('nik', 20)->unique(); // NIK
            $table->text('address'); // 'text' untuk alamat yang panjang

            // simpan sebagai string (path ke file) dan boleh 'null' (jika belum upload)
            $table->string('proposal')->nullable();
            $table->string('rekom')->nullable();

            $table->timestamps(); // Membuat `created_at` dan `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar');
    }
};
