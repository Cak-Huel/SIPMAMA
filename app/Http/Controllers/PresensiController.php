<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    // Tampilkan Halaman/Form Presensi
    public function index()
    {
        $user = Auth::user();

        // 1. Cek apakah punya data pendaftar yang LOLOS
        if (!$user->pendaftar || $user->pendaftar->status !== 'lolos') {
            return redirect()->route('dashboard')->with('error', 'Anda belum terdaftar sebagai peserta magang lolos.');
        }

        // 2. Cek apakah HARI INI masih dalam periode magang
        $today = Carbon::today();
        $start = Carbon::parse($user->pendaftar->tgl_start);
        $end = Carbon::parse($user->pendaftar->tgl_end);

        if (!$today->between($start, $end)) {
            return redirect()->route('dashboard')->with('error', 'Anda berada di luar periode magang aktif.');
        }

        // 3. Ambil Riwayat Presensi (Untuk ditampilkan di bawah form)
        $riwayat = Presensi::where('pendaftar_id', $user->pendaftar->id_pendaftar)
            ->latest()
            ->paginate(5);

        return view('presensi.index', compact('user', 'riwayat'));
    }

    // Simpan Data Presensi
    public function store(Request $request)
    {
        $user = Auth::user();
        $pendaftarId = $user->pendaftar->id_pendaftar;

        // --- VALIDASI KHUSUS ---
        $rules = [
            'jenis' => 'required|in:datang,pulang,izin,sakit',
            'latitude' => 'required', // Wajib ada koordinat
            'longitude' => 'required',
        ];

        // Logika Sakit: Wajib File
        if ($request->jenis == 'sakit') {
            $rules['bukti_file'] = 'required|file|mimes:pdf,jpg,png|max:2048';
        }

        // Logika Izin: Wajib Keterangan ATAU File
        if ($request->jenis == 'izin') {
            // Jika file kosong, keterangan wajib. Jika keterangan kosong, file wajib.
            if (!$request->hasFile('bukti_file')) {
                $rules['keterangan'] = 'required|string';
            }
        }

        $request->validate($rules, [
            'latitude.required' => 'Lokasi tidak terdeteksi. Pastikan GPS aktif.',
            'bukti_file.required' => 'Untuk status Sakit, wajib upload surat dokter.',
            'keterangan.required' => 'Untuk Izin, mohon isi keterangan atau upload dokumen.',
        ]);

        // --- PROSES UPLOAD FILE ---
        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $filename = 'presensi_' . $pendaftarId . '_' . time() . '.' . $file->extension();
            $filePath = $file->storeAs('presensi', $filename, 'public');
        }

        // --- SIMPAN KE DB ---
        Presensi::create([
            'pendaftar_id' => $pendaftarId,
            'tanggal' => Carbon::now()->toDateString(),
            'jam' => Carbon::now()->toTimeString(),
            'jenis' => $request->jenis,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'keterangan' => $request->keterangan,
            'bukti_file' => $filePath,
        ]);

        return back()->with('success', 'Presensi berhasil dicatat!');
    }
}
