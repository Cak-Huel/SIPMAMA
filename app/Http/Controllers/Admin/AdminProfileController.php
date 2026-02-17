<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Jika ada input 'password', berarti ini adalah permintaan untuk mengubah password
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password berhasil diperbarui.');
        }

        // Jika ada input 'nama' atau 'email', berarti ini adalah permintaan untuk mengubah profil
        if ($request->has('nama') || $request->has('email')) {
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            ]);

            $user->nama = $request->nama;
            $user->email = $request->email;
            $user->save();

            return back()->with('success', 'Profil akun berhasil diperbarui.');
        }

        // Jika tidak ada input yang relevan, kembali tanpa pesan
        return back();
    }
}
