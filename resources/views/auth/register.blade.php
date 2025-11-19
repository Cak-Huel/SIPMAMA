{{-- 1. Gunakan layout 'app.blade.php' --}}
@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">

        <div class="relative text-center mb-6 pb-4 border-b">
            
            <a href="{{ route('login') }}" class="absolute left-0 text-gray-600 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <h2 class="text-2xl font-bold">Daftar Akun</h2>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <input id="name" type="text" name="name"
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan Nama Lengkap" value="{{ old('name') }}" required autocomplete="name" autofocus>
                
                {{-- Menampilkan error validasi untuk 'name' --}}
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <input id="email" type="email" name="email"
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan Email" value="{{ old('email') }}" required autocomplete="email">

                {{-- Menampilkan error validasi untuk 'email' --}}
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <input id="password" type="password" name="password"
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan Password" required autocomplete="new-password">

                {{-- Menampilkan error validasi untuk 'password' --}}
                @error('password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <input id="password-confirm" type="password" name="password_confirmation"
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Konfirmasi Password" required autocomplete="new-password">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 cursor-pointer">
                Daftar
            </button>

        </form>

        <div class="mt-6 text-center text-sm">
            <p>Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                    Login
                </a>
            </p>
        </div>

    </div>
</div>
@endsection