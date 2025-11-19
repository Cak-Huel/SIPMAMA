{{-- 1. Memberi tahu Blade untuk menggunakan layout app.blade.php --}}
@extends('layouts.app')

{{-- 2. Memulai bagian 'content' yang akan dikirim ke @yield('content') --}}
@section('content')

<div class="min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">

        <div class="relative flex items-center justify-center mb-6">
            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard') }}" class="absolute left-0 text-gray-600 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-center">LOGIN SIPMAMA</h2>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <input id="email" type="email" name="email"
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="masukkan email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                
                {{-- Ini akan menampilkan error jika validasi email gagal (dari backend) --}}
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <input id="password" type="password" name="password"
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="masukkan password" required autocomplete="current-password">
            </div>

            <div class="text-right text-sm mb-6">
                {{-- Mengubah href ke '#' karena rute 'password.request' belum dibuat --}}
                <a href="#" class="text-blue-600 hover:underline">
                    Lupa password?
                </a>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 cursor-pointer">
                Login
            </button>

            <button type="button" disabled
                    class="w-full bg-gray-200 text-gray-500 p-3 rounded-lg font-semibold mt-4 cursor-not-allowed">
                Login dengan Google
            </button>

        </form>

        <div class="mt-6 text-center text-sm">
            <p>Belum memiliki akun? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                    Daftarkan akun
                </a>
            </p>
        </div>

    </div>
</div>

@endsection
{{-- 3. Menutup bagian 'content' --}}