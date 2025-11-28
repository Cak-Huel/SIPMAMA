<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            // Menyimpan catatan admin (alasan tolak/terima)
            $table->text('catatan')->nullable()->after('status');

            // Menyimpan ID Admin yang melakukan verifikasi (Audit Trail)
            $table->foreignId('verified_by')->nullable()->after('catatan')->constrained('users', 'id_user');

            // Menyimpan waktu verifikasi
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down()
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['catatan', 'verified_by', 'verified_at']);
        });
    }
};
