<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // --- PERBAIKI TABEL PENDAFTAR ---
        $pendaftarToFix = DB::table('pendaftar')
            ->where('proposal', 'like', 'public/%')
            ->orWhere('rekom', 'like', 'public/%')
            ->get();

        foreach ($pendaftarToFix as $pendaftar) {
            $newProposalPath = $pendaftar->proposal && Str::startsWith($pendaftar->proposal, 'public/') ? Str::after($pendaftar->proposal, 'public/') : $pendaftar->proposal;
            $newRekomPath = $pendaftar->rekom && Str::startsWith($pendaftar->rekom, 'public/') ? Str::after($pendaftar->rekom, 'public/') : $pendaftar->rekom;

            DB::table('pendaftar')
                ->where('id_pendaftar', $pendaftar->id_pendaftar)
                ->update(['proposal' => $newProposalPath, 'rekom' => $newRekomPath]);
        }

        // --- PERBAIKI TABEL PRESENSI ---
        $presensiToFix = DB::table('presensis')
            ->where('bukti_file', 'like', 'public/%')
            ->get();

        foreach ($presensiToFix as $presensi) {
            $newBuktiPath = Str::after($presensi->bukti_file, 'public/');
            DB::table('presensis')
                ->where('id', $presensi->id) // Asumsi primary key adalah 'id'
                ->update(['bukti_file' => $newBuktiPath]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Operasi ini adalah perbaikan data satu arah, tidak perlu di-reverse.
    }
};
