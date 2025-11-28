<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\Pengaturan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse; // Untuk Export CSV
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;

class AdminController extends Controller
{
    public function index()
    {
        // --- 1. DATA CARD 1: PENDAFTAR HARI INI ---
        $pendaftarHariIni = Pendaftar::whereDate('created_at', Carbon::today())->count();

        // --- 2. DATA CARD 2: MENUNGGU VERIFIKASI ---
        $menungguVerifikasi = Pendaftar::where('status', 'menunggu')->count();

        // --- 3. DATA CARD 3: SISA KUOTA BULAN INI ---
        $pengaturan = Pengaturan::where('key', 'kuota_global')->first();
        $kuotaMax = $pengaturan ? (int) $pengaturan->isi_teks : 7; // Default 7

        // 2. Hitung yang sedang 'Lolos' & 'Aktif' Bulan Ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $terisiBulanIni = Pendaftar::where('status', 'lolos')
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
                        // Kasus C: Full bulan
                        $q->where('tgl_start', '<', $startOfMonth)
                            ->where('tgl_end', '>', $endOfMonth);
                    });
            })
            ->count();

        $sisaKuota = $kuotaMax - $terisiBulanIni;
        if ($sisaKuota < 0) $sisaKuota = 0;

        // --- 4. DATA GRAFIK: TREN 6 BULAN TERAKHIR ---
        // Kita butuh array Label (Nama Bulan) dan Data (Jumlah)
        $grafikLabel = [];
        $grafikData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('F'); // Contoh: "Agustus"
            $year = $date->year;
            $month = $date->month;

            // Hitung pendaftar di bulan tersebut
            $count = Pendaftar::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $grafikLabel[] = $monthName; // Masukkan ke array label
            $grafikData[] = $count;      // Masukkan ke array data
        }

        // Kirim semua data ke View
        return view('admin.dashboard', compact(
            'pendaftarHariIni',
            'menungguVerifikasi',
            'sisaKuota',
            'grafikLabel',
            'grafikData'
        ));
    }

    // MENU PENDAFTAR (INDEX)----------------------------------------------------
    public function pendaftar(Request $request)
    {
        $query = Pendaftar::query();

        // --- Filter 1: Status Verifikasi ---
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- Filter 2: Periode (Bulan/Tahun) ---
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tgl_start', $request->bulan)
                ->whereYear('tgl_start', $request->tahun);
        }

        // Filter tanggal spesifik
        if ($request->filled('date')) {
            $query->wheredate('created_at', $request->date);
        }

        // --- Pencarian (Nama / Instansi) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('universitas', 'like', "%{$search}%");
            });
        }

        // Ambil data (Pagination 10 per halaman), urutkan terbaru
        $pendaftar = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pendaftar.index', compact('pendaftar'));
    }

    // DOWNLOAD DOKUMEN (Proposal/Rekom)----------------------------------------------------
    public function downloadDokumen($id, $jenis)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        // Tentukan path berdasarkan jenis
        $path = ($jenis == 'proposal') ? $pendaftar->proposal : $pendaftar->rekom;

        if (!$path || !Storage::exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Buat nama file download (Dynamic Naming)
        // Contoh: Proposal_NamaMahasiswa.pdf
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $cleanName = str_replace(' ', '_', $pendaftar->nama_lengkap);
        $downloadName = ucfirst($jenis) . '_' . $cleanName . '.' . $ext;

        return Storage::download($path, $downloadName);
    }

    // EKSPOR KE CSV (Ringan & Cepat)----------------------------------------------------
    public function exportCsv(Request $request)
    {
        // Gunakan filter yang sama seperti di index (copy-paste logic filter)
        $query = Pendaftar::query();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tgl_start', $request->bulan)->whereYear('tgl_start', $request->tahun);
        }

        $data = $query->get(); // Ambil semua data hasil filter

        // Buat Stream Response (agar server tidak berat load memori)
        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // Header Kolom CSV
            fputcsv($handle, ['No', 'Nama Lengkap', 'NIM', 'Universitas', 'Jurusan', 'Tgl Mulai', 'Tgl Selesai', 'Status', 'Sumber']);

            $no = 1;
            foreach ($data as $row) {
                fputcsv($handle, [
                    $no++,
                    $row->nama_lengkap,
                    "'" . $row->nim, // Tambah kutip agar excel membaca sebagai teks (bukan scientific number)
                    $row->universitas,
                    $row->jurusan,
                    $row->tgl_start,
                    $row->tgl_end,
                    strtoupper($row->status),
                    strtoupper($row->source)
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Rekap_Pendaftar_' . date('Y-m-d') . '.csv"');

        return $response;
    }

    // 6. HALAMAN DETAIL / VERIFIKASI ------------------------------------------------
    public function show($id)
    {
        // Ambil data pendaftar beserta data user-nya
        $pendaftar = Pendaftar::with('user')->findOrFail($id);

        return view('admin.pendaftar.show', compact('pendaftar'));
    }

    // 7. PREVIEW PDF (INLINE) ------------------------------------------------
    // Agar bisa dilihat di iframe tanpa download
    public function previewDokumen($id, $jenis)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $path = ($jenis == 'proposal') ? $pendaftar->proposal : $pendaftar->rekom;

        if (!$path || !Storage::exists($path)) {
            abort(404);
        }

        // 'response()->file()' akan membuka file di browser (inline)
        return response()->file(Storage::path($path));
    }

    // 8. PROSES VERIFIKASI (UPDATE STATUS) ------------------------------------------------
    public function updateStatus(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        // Validasi Input
        $request->validate([
            'status' => 'required|in:lolos,ditolak',
            'catatan' => 'required|string',
        ]);

        // Update Data
        $pendaftar->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'verified_by' => Auth::id(), // ID Admin yang login
            'verified_at' => now(),      // Waktu sekarang
        ]);

        // (Opsional) Di sini nanti kita bisa kirim Email Notifikasi ke Mahasiswa

        return redirect()->route('admin.pendaftar.index')
            ->with('success', 'Status pendaftar berhasil diperbarui menjadi: ' . strtoupper($request->status));
    }

    // HALAMAN PRESENSI (Gantikan Pengaturan) ------------------------------------------------
    public function presensi(Request $request)
    {
        $query = Presensi::with('pendaftar'); // Eager load relasi pendaftar

        // Filter 1: Tanggal (Default Hari Ini jika kosong)
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            // Opsional: Jika ingin default hari ini, uncomment baris ini:
            // $query->whereDate('tanggal', Carbon::today());
        }

        // Filter 2: Status (Datang/Pulang/Sakit/Izin)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter 3: Nama Mahasiswa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pendaftar', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $data = $query->latest('tanggal')->latest('jam')->paginate(15)->withQueryString();

        return view('admin.presensi.index', compact('data'));
    }

    // 10. DOWNLOAD BUKTI SAKIT/IZIN ------------------------------------------------
    public function downloadBukti($id)
    {
        $presensi = Presensi::findOrFail($id);

        if (!$presensi->bukti_file || !Storage::exists($presensi->bukti_file)) {
            return back()->with('error', 'File bukti tidak ditemukan.');
        }

        return Storage::download($presensi->bukti_file);
    }

    // 11. EKSPOR PRESENSI (CSV) ------------------------------------------------
    public function exportPresensiCsv(Request $request)
    {
        // 1. CEK APAKAH ADA FILTER?
        // cek parameter 'tanggal', 'jenis', atau 'search'
        if (!$request->filled('tanggal') && !$request->filled('jenis') && !$request->filled('search')) {
            return back()->with('error', 'Mohon terapkan minimal satu filter (Tanggal, Status, atau Nama) sebelum mengunduh data.');
        }

        // 2. QUERY DATA (Sama persis dengan index presensi)
        $query = Presensi::with('pendaftar');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pendaftar', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $data = $query->latest('tanggal')->latest('jam')->get(); // Ambil semua (tanpa pagination)

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data yang sesuai filter untuk diunduh.');
        }

        // 3. GENERATE CSV
        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // Header Excel
            fputcsv($handle, ['Tanggal', 'Jam', 'Nama Mahasiswa', 'Status', 'Lokasi (Lat,Long)', 'Keterangan', 'Status Waktu']);

            foreach ($data as $row) {
                // Cek telat
                $statusWaktu = ($row->jenis == 'datang' && $row->jam > '07:30:00') ? 'TERLAMBAT' : 'TEPAT WAKTU';

                fputcsv($handle, [
                    $row->tanggal,
                    $row->jam,
                    $row->pendaftar->nama_lengkap,
                    strtoupper($row->jenis),
                    $row->latitude . ',' . $row->longitude,
                    $row->keterangan ? $row->keterangan : '-', // Teks keterangan
                    $statusWaktu
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Rekap_Presensi_' . date('Y-m-d_His') . '.csv"');

        return $response;
    }
}
