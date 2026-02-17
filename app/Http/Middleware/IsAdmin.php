<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Izinkan Admin ATAU Operator
        $userRole = Auth::user()->role;
        if ($userRole !== 'admin' && $userRole !== 'operator') {
            // Jika bukan admin DAN bukan operator (misal mahasiswa).
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
        }

        // 3. Jika lolos, silakan lanjut
        return $next($request);
    }
}
