<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class PenggunaController extends Controller
{
    // Hanya Admin (bukan operator) yang boleh akses ini
    public function index()
    {
        Gate::authorize('admin-only'); // Pastikan Gate sudah didefinisikan di AppServiceProvider

        // Ambil user yang role-nya 'operator'
        $operators = User::where('role', 'operator')->latest()->get();
        return view('admin.pengguna.index', compact('operators'));
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'operator', // Otomatis jadi operator
        ]);

        return back()->with('success', 'Akun Operator berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Gate::authorize('admin-only');

        $user = User::findOrFail($id);
        if ($user->role !== 'operator') {
            return back()->with('error', 'Hanya akun operator yang boleh dihapus di sini.');
        }

        $user->delete();
        return back()->with('success', 'Akun Operator berhasil dihapus.');
    }
}
