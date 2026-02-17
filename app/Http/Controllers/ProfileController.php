<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Pendaftar;
use App\Models\User;

class ProfileController extends Controller
{

    // Menampilkan halaman profil.
    public function show()
    {
        // 1. Ambil ID user yang sedang login
        $userId = Auth::user()->id_user;

        // 2. Ambil data user dari database SECARA EKSPLISIT
        //    gunakan 'with()' untuk eager-load relasi 'pendaftar'
        $user = User::with('pendaftar')->where('id_user', $userId)->first();

        // 3. Failsafe (jika user tidak ditemukan)
        if (!$user) {
            return redirect()->route('home')->with('error', 'Gagal memuat data pengguna.');
        }

        // 4. Kirim data ke view
        return view('profil', [
            'user' => $user,
            'pendaftar' => $user->pendaftar
        ]);
    }

    // Meng-upload surat rekomendasi dari halaman profil.
    public function UploadProposal(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'proposal' => 'required|file|mimes:pdf|max:5120', // Wajib, PDF, Maks 5MB
        ]);

        // 2. Temukan data pendaftar
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // 3. Hapus file lama jika ada (opsional tapi bagus)
        if ($pendaftar->proposal) {
            Storage::delete($pendaftar->proposal);
        }

        // 4. Upload file baru
        $file = $request->file('proposal');
        $filename = $pendaftar->nim . '_proposal_' . time() . '.' . $file->extension();
        $proposalPath = $file->storeAs('proposal', $filename, 'public');

        // 5. Update database
        $pendaftar->proposal = $proposalPath;
        $pendaftar->save();

        return back()->with('success', 'Proposal berhasil di-upload!');
    }

    // Menghapus (anonimisasi) akun pengguna.
    public function destroy(Request $request)
    {
        // 1. Validasi password (jika kamu sudah implementasi modalnya)
        // $request->validate([
        //     'password_confirm' => 'required|current_password',
        // ], [
        //     'password_confirm.current_password' => 'Password yang Anda masukkan salah.'
        // ]);

        // 2. Ambil ID user yang sedang login
        $userId = Auth::user()->id_user;

        // 3. Ambil MODEL Eloquent user SEBELUM logout
        //    (Ini adalah kunci untuk menghilangkan garis merah di 'delete()')
        $userModel = User::find($userId);

        if (!$userModel) {
            return back()->with('error', 'Gagal menemukan data user untuk dihapus.');
        }

        // 4. Anonimisasi Data Pendaftar (milik user ini)
        Pendaftar::where('user_id', $userId)->update([
            'nama_lengkap' => 'Pengguna Anonim',
            'nik' => '0000000000000000',
            'wa' => '0000000000',
            'nim' => '00000000',
            'address' => 'Anonim',
            'user_id' => null
        ]);

        // 5. Logout user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 6. HAPUS model user (SEKARANG aman dan tidak ada garis merah)
        $userModel->delete();

        return redirect()->route('home')->with('success', 'Akun Anda telah berhasil dihapus.');
    }
}
