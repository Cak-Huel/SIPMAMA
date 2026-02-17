@extends('layouts.admin')
@section('header', 'Kelola Operator')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-sm sticky top-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Operator Baru</h3>
            <form method="POST" action="{{ route('admin.pengguna.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full border-gray-300 rounded-lg p-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Login</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded-lg p-2 text-sm" required>
                    </div>
                    
                    <div x-data="{ show: false }">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative mt-1">
                            <input :type="show ? 'text' : 'password'" name="password" class="w-full border-gray-300 rounded-lg p-2 text-sm pr-10" required>
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500">
                                <span x-show="!show">👁️</span><span x-show="show" style="display:none">🚫</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Operator</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($operators as $op)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $op->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $op->email }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.pengguna.destroy', $op->id_user) }}" method="POST" onsubmit="return confirm('Hapus akses operator ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900 text-sm font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada operator.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection