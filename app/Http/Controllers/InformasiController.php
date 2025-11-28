<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class InformasiController extends Controller
{

    // HALAMAN UTAMA (TABS) -------------------------------------------
    public function index()
    {
        // 1. AMBIL SETTING KUOTA GLOBAL (Default 7 jika belum diatur)
        $kuotaSetting = Pengaturan::firstOrCreate(
            ['key' => 'kuota_global'],
            ['judul' => 'Kuota Maksimal Magang', 'isi_teks' => '7']
        );
        $kuotaMax = (int) $kuotaSetting->isi_teks;

        // 2. HITUNG KETERSEDIAAN 12 BULAN KE DEPAN
        // Logika: Cek berapa orang yang 'Lolos' dan 'Aktif' di bulan tersebut
        $lowonganList = [];
        $now = Carbon::now();

        for ($i = 0; $i < 12; $i++) {
            $date = $now->copy()->addMonths($i);

            // Tentukan awal dan akhir bulan yang sedang dicek
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // QUERY AJAIB: Mencari irisan tanggal (Overlap)
            // "Ambil pendaftar LOLOS yang masa magangnya BERSINGGUNGAN dengan bulan ini"
            $terisi = Pendaftar::where('status', 'lolos')
                ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                        // Kasus A: Magang mulai di dalam bulan ini
                        $q->whereBetween('tgl_start', [$startOfMonth, $endOfMonth]);
                    })
                        ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                            // Kasus B: Magang selesai di dalam bulan ini
                            $q->whereBetween('tgl_end', [$startOfMonth, $endOfMonth]);
                        })
                        ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                            // Kasus C: Magang mulai sebelum bulan ini DAN selesai setelah bulan ini (Full 1 bulan ada)
                            $q->where('tgl_start', '<', $startOfMonth)
                                ->where('tgl_end', '>', $endOfMonth);
                        });
                })
                ->count();

            $sisa = $kuotaMax - $terisi;

            // Status warna/label untuk UI
            $statusLabel = 'Tersedia';
            $statusColor = 'text-green-600';

            if ($sisa <= 0) {
                $sisa = 0;
                $statusLabel = 'Penuh';
                $statusColor = 'text-red-600';
            } elseif ($sisa <= 2) {
                $statusLabel = 'Hampir Penuh';
                $statusColor = 'text-yellow-600';
            }

            $lowonganList[] = (object) [
                'nama_bulan' => $date->translatedFormat('F Y'),
                'kuota_max' => $kuotaMax,
                'terisi' => $terisi, // Estimasi terisi di bulan itu
                'sisa' => $sisa,
                'status_label' => $statusLabel,
                'status_color' => $statusColor
            ];
        }

        // --- KONTEN STATIS LAINNYA ---
        $syarat = Pengaturan::firstOrCreate(['key' => 'syarat_ketentuan'], ['judul' => 'Syarat', 'isi_teks' => '-']);
        $poster = Pengaturan::firstOrCreate(['key' => 'poster_utama'], ['judul' => 'Poster', 'file_poster' => null]);

        return view('admin.informasi.index', compact('lowonganList', 'kuotaSetting', 'syarat', 'poster'));
    }

    // UPDATE KUOTA GLOBAL --------------------------------------------------------
    public function updateKuotaGlobal(Request $request)
    {
        $request->validate([
            'kuota_global' => 'required|integer|min:1'
        ]);

        Pengaturan::where('key', 'kuota_global')->update([
            'isi_teks' => $request->kuota_global
        ]);

        return back()->with('success', 'Kuota Global berhasil diperbarui. Perhitungan ketersediaan bulan depan otomatis menyesuaikan.');
    }

    // fungsi updateKonten() --------------------------------------------------------
    public function updateKonten(Request $request)
    {
        // Update Syarat
        Pengaturan::where('key', 'syarat_ketentuan')->update(['isi_teks' => $request->syarat]);

        // UPDATE POSTER DENGAN KOMPRESI
        if ($request->hasFile('poster')) {
            $request->validate(['poster' => 'image|max:10240']); // Max 10MB

            // 1. Hapus poster lama
            $oldPoster = Pengaturan::where('key', 'poster_utama')->first();
            if ($oldPoster->file_poster && Storage::exists($oldPoster->file_poster)) {
                Storage::delete($oldPoster->file_poster);
            }

            // 2. Siapkan Manager Gambar
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('poster'));

            // 3. Resize (Kecilkan resolusi jika terlalu besar)
            // scaleDown: hanya mengecilkan jika gambar lebih besar dari 800px width
            $image->scaleDown(width: 800);

            // 4. Encode ke WebP (Quality 80%)
            $encoded = $image->toWebp(quality: 80);

            // 5. Buat nama file unik dengan akhiran .webp
            $filename = 'poster_' . time() . '.webp';
            $path = 'posters/' . $filename;

            // 6. Simpan ke Storage
            Storage::disk('public')->put($path, $encoded);

            // 7. Simpan path ke Database
            Pengaturan::where('key', 'poster_utama')->update(['file_poster' => $path]);
        }

        return back()->with('success', 'Konten statis berhasil diperbarui.');
    }
}
