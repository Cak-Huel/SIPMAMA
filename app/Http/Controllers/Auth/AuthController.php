<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // import model user
use Illuminate\Support\Facades\Hash; //import hash untuk enkripsi
use Illuminate\Support\Facades\Auth; // import auth untuk login
use Illuminate\Validation\Rules; // import aturan validasi
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Fungsi ini akan dipanggil untuk MENAMPILKAN halaman login
    public function showLoginForm()
    {
        // mengembalikan 'view'
        return view('auth.login');
    }

    // Fungsi ini akan dipanggil untuk MENAMPILKAN halaman register
    public function showRegisterForm()
    {
        // Mengembalikan 'view' register
        return view('auth.register');
    }

    // untuk memproses data register
    public function register(Request $request)
    {
        // 1. Validasi input dari form registrasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Buat user baru di database
        $user = User::create([
            'nama' => $request->name, // Menggunakan 'name' dari form untuk kolom 'nama'
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Memicu event 'Registered'. Laravel akan otomatis mengirim email verifikasi
        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            // Email gagal kirim, tapi biarkan user tetap lanjut (bisa minta ulang nanti)
        }

        // 4. Login-kan user yang baru dibuat
        Auth::login($user);

        // 5. **INI BAGIAN KUNCI**: Arahkan pengguna ke halaman notifikasi verifikasi
        return redirect()->route('verification.notice');
    }

    // untuk memproses data login
    public function login(Request $request)
    {
        // 1. Validasi data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Otomatis hash password inputan dan mencocokkannya
        if (Auth::attempt($credentials)) {

            // Jika berhasil...
            $request->session()->regenerate(); // Regenerate session (keamanan)

            // 3. Cek Role & Redirect (RBAC)
            $user = Auth::user();
            if ($user->role == 'admin' || $user->role == 'operator') {
                return redirect('/admin/dashboard'); // Arahkan admin
            }

            return redirect('/dashboard'); // Arahkan mahasiswa
        }

        // 4. Kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email'); // agar emailnya tidak hilang
    }

    // untuk logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
