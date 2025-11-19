<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // Untuk info user login
use Illuminate\Support\Facades\Storage; // Untuk mengelola file
use App\Models\Pendaftar;                 // Model yang baru kita buat
use Illuminate\Support\Str;             // Untuk membuat nama file unik (opsional)

class FormulirController extends Controller
{

    // Fungsi MENAMPILKAN formulir
    public function showForm()
    {
        // 1. CEK APAKAH USER SUDAH PERNAH DAFTAR?
        // Kita tidak ingin user bisa mendaftar dua kali
        $user_id = Auth::user()->id_user;
        $pendaftar = Pendaftar::where('user_id', $user_id)->first();

        if ($pendaftar) {
            // jika status lolos atau menunggu, blokir
            if ($pendaftar->status === 'lolos' || $pendaftar->status === 'menunggu') {
                return redirect()->route('profil.show')->with('info', 'Anda sudah terdaftar. Cek status pendaftaran Anda di halaman profil.');
            }

            // jika status ditolak, izinkan daftar ulang
            if ($pendaftar->status === 'ditolak') {
                $pendaftar->delete(); // Hapus data pendaftar lama
                return view('formulir')->with('info', 'Pendaftaran Anda sebelumnya ditolak. Silakan isi kembali formulir untuk periode baru.');
            }
        }

        // Jika belum, tampilkan view formulir
        return view('formulir');
    }


    // Fungsi untuk MENERIMA (SUBMIT) data formulir
    public function submitForm(Request $request)
    {
        // 1. CEK Pendaftaran Ganda (double check)
        $user_id = Auth::user()->id_user;
        $existingRegistration = Pendaftar::where('user_id', $user_id)->first();
        if ($existingRegistration) {
            return redirect()->route('home')->with('error', 'Anda sudah melakukan pendaftaran.');
        }

        // 2. VALIDASI DATA
        $validatedData = $request->validate([
            // Grup 1: Data Pribadi (dari form 'nama', 'nik', 'wa', 'address')
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|digits:16|unique:pendaftar,nik', // NIK harus 16 digit & unik
            'wa' => 'required|string|min:10|max:15',
            'address' => 'required|string|max:500',

            // Grup 2: Data Akademik
            'nim' => 'required|string|max:20|unique:pendaftar,nim', // NIM harus unik
            'universitas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',

            // Grup 3: Periode
            'tgl_start' => 'required|date|after:today', // Tgl mulai harus setelah hari ini
            'tgl_end' => 'required|date|after:tgl_start', // Tgl selesai harus setelah tgl mulai

            // Grup 4: Dokumen (PDF, Maks 5MB)
            'proposal' => 'required|file|mimes:pdf|max:5120',
            'rekom' => 'nullable|file|mimes:pdf|max:5120', // <-- 'nullable' (opsional)

            // Grup 5: Konfirmasi
            'konfirmasi' => 'required|accepted',
        ]);

        // 3. PROSES UPLOAD FILE (PROPOSAL - WAJIB)
        $proposalPath = null;
        if ($request->hasFile('proposal')) {
            $file = $request->file('proposal');
            // Buat nama unik: nama_proposal_timestamp.pdf
            $filename = $request->nama . '_proposal_' . time() . '.' . $file->extension();
            // Simpan di: storage/app/public/proposals
            $proposalPath = $file->storeAs('public/proposals', $filename);
        }

        // 4. PROSES UPLOAD FILE (REKOMENDASI - OPSIONAL)
        $rekomPath = null;
        if ($request->hasFile('rekom')) {
            $file = $request->file('rekom');
            $filename = $request->nama . '_rekom_' . time() . '.' . $file->extension();
            // Simpan di: storage/app/public/rekomendasi
            $rekomPath = $file->storeAs('public/rekomendasi', $filename);
        }

        // 5. SIMPAN DATA KE DATABASE
        Pendaftar::create([
            'user_id' => $user_id,
            'nama_lengkap' => $validatedData['nama'], // 'nama_lengkap' (DB) diisi oleh 'nama' (Form)
            'nik' => $validatedData['nik'],
            'wa' => $validatedData['wa'],
            'address' => $validatedData['address'],
            'nim' => $validatedData['nim'],
            'universitas' => $validatedData['universitas'],
            'jurusan' => $validatedData['jurusan'],
            'tgl_start' => $validatedData['tgl_start'],
            'tgl_end' => $validatedData['tgl_end'],
            'proposal' => $proposalPath, // Simpan path-nya
            'rekom' => $rekomPath,       // Simpan path-nya (bisa null)
            // 'status' otomatis 'menunggu' (sesuai default migrasi kita)
        ]);

        // 6. REDIRECT DENGAN PESAN SUKSES
        // Kita arahkan ke dashboard dengan pesan flash
        return redirect()->route('home')->with('success', 'Pendaftaran Anda berhasil disubmit! Silakan cek status Anda secara berkala di Halaman Profil.');
    }
}
