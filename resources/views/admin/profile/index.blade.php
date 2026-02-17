@extends('layouts.admin')
@section('header', 'Profil Akun')

@section('content')
{{--
    Alpine.js digunakan untuk mengelola state UI untuk mode edit profil dan password.
    - editProfile: mengontrol visibilitas form edit profil.
    - editPassword: mengontrol visibilitas form ganti password.
--}}
<div class="bg-white p-8 rounded-lg shadow-md w-full h-full" x-data="{ editProfile: false, editPassword: false }">

    <h2 class="text-2xl font-bold mb-6 border-b pb-4">Profil Anda</h2>

    {{-- Bagian Informasi Profil --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Informasi Akun</h3>

        {{-- Tampilan Biasa (Read-only) --}}
        <div x-show="!editProfile" class="space-y-4">
            <dl class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-2">
                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                <dd class="text-sm text-gray-900 col-span-2 lg:col-span-3">{{ $user->nama }}</dd>

                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="text-sm text-gray-900 col-span-2 lg:col-span-3">{{ $user->email }}</dd>
            </dl>
        </div>

        {{-- Form Edit Profil --}}
        <div x-show="editProfile" x-transition style="display: none;">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="max-w-md">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input id="nama" type="text" name="nama" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email', $user->email) }}" required>
                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                        Simpan Perubahan
                    </button>
                    <button type="button" @click="editProfile = false" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bagian Keamanan (Password) --}}
    <div class="border-t pt-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Keamanan</h3>

        {{-- Tombol Ubah Password --}}
        <div x-show="!editPassword">
            <button type="button" @click="editPassword = true; editProfile = false" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                Ubah Password
            </button>
        </div>

        {{-- Form Edit Password --}}
        <div x-show="editPassword" x-transition style="display: none;">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="max-w-md">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input id="password" type="password" name="password" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 6 karakter" required>
                    @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ketik ulang password baru" required>
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                        Simpan Password
                    </button>
                    <button type="button" @click="editPassword = false" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tombol Logout --}}
    <div class="mt-10 border-t pt-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 cursor-pointer">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
