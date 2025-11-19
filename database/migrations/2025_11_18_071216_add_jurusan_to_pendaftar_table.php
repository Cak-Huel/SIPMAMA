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
        // Fungsi 'up' (maju)
        Schema::table('pendaftar', function (Blueprint $table) {
            // Tambahkan kolom 'jurusan' (tipe string)
            // Kita letakkan 'after' (setelah) kolom 'universitas' agar rapi
            $table->string('jurusan')->after('universitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fungsi 'down' (mundur/batal)
        Schema::table('pendaftar', function (Blueprint $table) {
            // Jika kita perlu 'rollback', hapus kolom 'jurusan'
            $table->dropColumn('jurusan');
        });
    }
};
