<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Pendaftar;
use Carbon\Carbon;
use App\Models\Galeri;
use App\Models\Faq;
use App\Models\KuotaPeriode;

class PageController extends Controller
{
    // DASHBOARD ------------------------------------------------
    public function index()
    {
        // --- 1. AMBIL KUOTA MAKSIMUM (Global) ---
        $pengaturan = Pengaturan::where('key', 'kuota_global')->first();
        $kuotaMax = $pengaturan ? (int) $pengaturan->isi_teks : 7;

        // --- 2. HITUNG MAGANG BERJALAN ---
        // Logic: Status 'lolos' DAN Tanggal Selesai masih di masa depan (atau hari ini)
        $magangBerjalan = Pendaftar::where('status', 'lolos')
            ->whereDate('tgl_end', '>=', Carbon::today())
            ->count();

        // --- 3. HITUNG PERKIRAAN TERSEDIA ---
        $perkiraanTersedia = "Sekarang"; // Default jika masih longgar

        // Jika kuota penuh (atau over quota), kita cari kapan slot berikutnya kosong
        if ($magangBerjalan >= $kuotaMax) {

            // Cari 1 orang anak magang yang 'tgl_end'-nya paling cepat berakhir
            $slotTerdekat = Pendaftar::where('status', 'lolos')
                ->whereDate('tgl_end', '>=', Carbon::today())
                ->orderBy('tgl_end', 'asc') // Urutkan dari yang mau selesai duluan
                ->first();

            if ($slotTerdekat) {
                // Slot tersedia 1 hari setelah dia selesai
                // Format: "Oktober 2025"
                $tanggalTersedia = Carbon::parse($slotTerdekat->tgl_end)->addDay();
                $perkiraanTersedia = $tanggalTersedia->translatedFormat('F Y');
            } else {
                $perkiraanTersedia = "Penuh"; // Fallback jika error data
            }
        }

        // Kirim semua variabel ke View 'dashboard'
        return view('dashboard', compact('kuotaMax', 'magangBerjalan', 'perkiraanTersedia'));
    }

    // HALAMAN INFORMASI ---------------------------------------------
    public function informasi()
    {
        // 1. AMBIL KUOTA GLOBAL (DEFAULT)
        $kuotaSetting = Pengaturan::firstOrCreate(['key' => 'kuota_global'], ['isi_teks' => '7']);
        $defaultKuota = (int) $kuotaSetting->isi_teks;

        // 2. GENERATE DATA 4 BULAN KE DEPAN
        $jadwalKuota = [];
        $now = Carbon::now();

        for ($i = 0; $i < 4; $i++) {
            $date = $now->copy()->addMonths($i);
            // Cek apakah ada kuota khusus bulan ini?
            $kuotaKhusus = KuotaPeriode::where('bulan', $date->month)
                ->where('tahun', $date->year)
                ->first();
            $kuotaMax = $kuotaKhusus ? $kuotaKhusus->kuota : $defaultKuota;

            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // 3. HITUNG YANG TERISI (Logic Overlap Tanggal)
            // Mencari pendaftar 'lolos' yang masa magangnya memakan kuota bulan ini
            $terisi = Pendaftar::where('status', 'lolos')
                ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                        // Kasus A: Mulai di bulan ini
                        $q->whereBetween('tgl_start', [$startOfMonth, $endOfMonth]);
                    })
                        ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                            // Kasus B: Selesai di bulan ini
                            $q->whereBetween('tgl_end', [$startOfMonth, $endOfMonth]);
                        })
                        ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                            // Kasus C: Full bulan (Mulai sebelum & Selesai sesudah)
                            $q->where('tgl_start', '<', $startOfMonth)
                                ->where('tgl_end', '>', $endOfMonth);
                        });
                })
                ->count();

            // 4. HITUNG SISA
            $sisa = $kuotaMax - $terisi;
            if ($sisa < 0) $sisa = 0;

            // 5. TENTUKAN STATUS & WARNA
            $statusText = 'TERSEDIA';
            $statusColor = 'bg-green-100 text-green-700'; // Hijau

            if ($sisa == 0) {
                $statusText = 'PENUH';
                $statusColor = 'bg-red-100 text-red-700'; // Merah
            } elseif ($sisa <= 2) {
                $statusText = 'HAMPIR PENUH';
                $statusColor = 'bg-yellow-100 text-yellow-700'; // Kuning
            }

            // Masukkan ke array
            $jadwalKuota[] = (object) [
                'periode' => $date->translatedFormat('F Y'), // Contoh: "Oktober 2025"
                'kuota_awal' => $kuotaMax,
                'sisa' => $sisa,
                'status_text' => $statusText,
                'status_color' => $statusColor
            ];
        }

        // 3. AMBIL KONTEN STATIS (TERMASUK $syarat)
        $syarat = Pengaturan::firstOrCreate(['key' => 'syarat_ketentuan'], ['judul' => 'Syarat', 'isi_teks' => '-']);

        // Kirim data ke view 'informasi'
        return view('informasi', compact('jadwalKuota', 'syarat'));
    }

    // FUNGSI HALAMAN GALERI ---------------------------------------------
    public function showGaleri()
    {
        // Ambil semua galeri, urutkan dari yang terbaru
        $galeri = Galeri::latest()->paginate(9); // Tampilkan 9 foto per halaman

        return view('galeri', compact('galeri'));
    }

    // FUNGSI HALAMAN FAQ ---------------------------------------------
    public function showFaq()
    {
        $faqs = Faq::all(); // Ambil semua data
        return view('faq', compact('faqs'));
    }
}
