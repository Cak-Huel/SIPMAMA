<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Faq;
use App\Models\Galeri;
use App\Models\KuotaPeriode;
use Illuminate\Support\Facades\Gate;

class InformasiController extends Controller
{

    // HALAMAN UTAMA (TABS) -------------------------------------------
    public function index()
    {
        // 1. AMBIL KUOTA DEFAULT (GLOBAL)
        $kuotaSetting = Pengaturan::firstOrCreate(
            ['key' => 'kuota_global'],
            ['judul' => 'Kuota Default', 'isi_teks' => '7']
        );
        $defaultKuota = (int) $kuotaSetting->isi_teks;

        // 2. LOOPING 12 BULAN KE DEPAN
        $lowonganList = [];
        $now = Carbon::now();

        for ($i = 0; $i < 12; $i++) {
            $date = $now->copy()->addMonths($i);
            $bulan = $date->month;
            $tahun = $date->year;

            // --- LOGIKA BARU: CEK KUOTA SPESIFIK ---
            // Cari apakah bulan ini punya settingan khusus di database?
            $kuotaKhusus = KuotaPeriode::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            // Jika ada khusus, pakai itu. Jika tidak, pakai default global.
            $kuotaMax = $kuotaKhusus ? $kuotaKhusus->kuota : $defaultKuota;

            // --- HITUNG TERISI (LOGIKA LAMA - OVERLAP) ---
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $terisi = Pendaftar::where('status', 'lolos')
                ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->whereBetween('tgl_start', [$startOfMonth, $endOfMonth]);
                    })
                        ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                            $q->whereBetween('tgl_end', [$startOfMonth, $endOfMonth]);
                        })
                        ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                            $q->where('tgl_start', '<', $startOfMonth)
                                ->where('tgl_end', '>', $endOfMonth);
                        });
                })
                ->count();

            $sisa = $kuotaMax - $terisi;

            // Status Warna
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
                'bulan' => $bulan, // Butuh untuk form update
                'tahun' => $tahun, // Butuh untuk form update
                'nama_bulan' => $date->translatedFormat('F Y'),
                'kuota_max' => $kuotaMax,
                'is_custom' => $kuotaKhusus ? true : false, // Penanda visual
                'terisi' => $terisi,
                'sisa' => $sisa,
                'status_label' => $statusLabel,
                'status_color' => $statusColor
            ];
        }

        // --- KONTEN STATIS LAINNYA ---
        $syarat = Pengaturan::firstOrCreate(['key' => 'syarat_ketentuan'], ['judul' => 'Syarat', 'isi_teks' => '-']);
        $poster = Pengaturan::firstOrCreate(['key' => 'poster_utama'], ['judul' => 'Poster', 'file_poster' => null]);
        $faqs = Faq::all();
        $galeris = Galeri::latest()->get();

        return view('admin.informasi.index', compact('lowonganList', 'kuotaSetting', 'syarat', 'poster', 'faqs', 'galeris'));
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

        // CEGAH OPERATOR MASUK SINI
        Gate::authorize('admin-only');

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

    // FUNGSI CRUD FAQ ------------------------------------------------
    public function storeFaq(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        Faq::create($request->all());

        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function destroyFaq($id)
    {
        Faq::destroy($id);
        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    // CRUD GALERI ------------------------------------------------

    public function storeGaleri(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'periode' => 'required|string|max:255', // Misal: "Jan - Mar 2025"
            'foto' => 'required|image|max:10240',   // Max 10MB (Aman, nanti kita kecilkan)
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            // 1. Siapkan Manager Gambar
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('foto'));

            // 2. Resize & Convert ke WebP
            $image->scaleDown(width: 800); // Lebar maks 800px
            $encoded = $image->toWebp(quality: 80);

            // 3. Simpan File
            $filename = 'galeri_' . time() . '_' . uniqid() . '.webp';
            $path = 'galeris/' . $filename;
            Storage::disk('public')->put($path, $encoded);

            // 4. Simpan ke Database
            Galeri::create([
                'judul' => $request->judul,
                'periode' => $request->periode,
                'deskripsi' => $request->deskripsi,
                'foto_path' => $path
            ]);
        }

        return back()->with('success', 'Foto berhasil ditambahkan ke Galeri (Format WebP).');
    }

    public function destroyGaleri($id)
    {
        $item = Galeri::findOrFail($id);

        // Hapus file fisik
        if ($item->foto_path && Storage::exists($item->foto_path)) {
            Storage::delete($item->foto_path);
        }

        // Hapus data DB
        $item->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    // UPDATE KUOTA PERIODE KHUSUS --------------------------------------------------------
    public function updateKuotaPeriode(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
            'kuota' => 'required|integer|min:0'
        ]);

        // Update atau Buat Baru (Upsert)
        KuotaPeriode::updateOrCreate(
            ['bulan' => $request->bulan, 'tahun' => $request->tahun], // Kondisi pencarian
            ['kuota' => $request->kuota] // Nilai yang diupdate
        );

        // CEGAH OPERATOR MASUK SINI
        Gate::authorize('admin-only');

        return back()->with('success', 'Kuota untuk periode tersebut berhasil diubah.');
    }
}
