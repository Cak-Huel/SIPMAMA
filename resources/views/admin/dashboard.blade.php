@extends('layouts.admin')

@section('header', 'Dashboard Ringkasan')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-600 flex flex-col justify-between">
            <div>
                <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Pendaftar Baru (Hari Ini)</h3>
                <div class="flex items-center mt-2">
                    <span class="text-4xl font-bold text-gray-800">{{ $pendaftarHariIni }}</span>
                    <span class="ml-2 text-sm text-gray-500">Orang</span>
                </div>
            </div>
            <div class="mt-4">
                {{-- PERBAIKAN LINK: Filter berdasarkan tanggal hari ini --}}
                <a href="{{ route('admin.pendaftar.index', ['date' => date('Y-m-d')]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center transition-colors">
                    Lihat Pendaftar Baru
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 flex flex-col justify-between">
            <div>
                <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Menunggu Verifikasi</h3>
                <div class="flex items-center mt-2">
                    <span class="text-4xl font-bold text-gray-800">{{ $menungguVerifikasi }}</span>
                    <span class="ml-2 text-sm text-gray-500">berkas</span>
                </div>
            </div>
            <div class="mt-4">
                {{-- PERBAIKAN LINK: Filter berdasarkan status 'menunggu' --}}
                <a href="{{ route('admin.pendaftar.index', ['status' => 'menunggu']) }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-800 flex items-center transition-colors">
                    Mulai Verifikasi
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </a>
            </div>
        </div>

        @php
            // Logika Warna: Merah jika 0, Kuning jika <= 2, Hijau jika aman
            $quotaColor = 'border-green-500';
            $textColor = 'text-green-600';
            if($sisaKuota <= 0) {
                $quotaColor = 'border-red-500';
                $textColor = 'text-red-600';
            } elseif ($sisaKuota <= 2) {
                $quotaColor = 'border-yellow-500';
                $textColor = 'text-yellow-600';
            }
        @endphp
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 {{ $quotaColor }} flex flex-col justify-between">
            <div>
                <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Sisa Kuota Bulan Ini</h3>
                <div class="flex items-center mt-2">
                    <span class="text-4xl font-bold {{ $textColor }}">{{ $sisaKuota }}</span>
                    <span class="ml-2 text-sm text-gray-500">Kursi</span>
                </div>
            </div>
            <div class="mt-4">
                {{-- Link ke menu informasi untuk edit kuota --}}
                <a href="{{ route('admin.informasi.index') }}" class="text-xs text-gray-400 hover:text-gray-600 underline">
                    Kelola Kapasitas
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Pendaftaran (6 Bulan Terakhir)</h3>
        
        <div class="relative h-80 w-full">
            <canvas id="registrationChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('registrationChart').getContext('2d');
        const registrationChart = new Chart(ctx, {
            type: 'bar', // Tipe grafik: Batang
            data: {
                // Ambil data label (Bulan) dari Controller
                labels: {!! json_encode($grafikLabel) !!}, 
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    // Ambil data angka dari Controller
                    data: {!! json_encode($grafikData) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.6)', // Warna Biru Transparan
                    borderColor: 'rgba(59, 130, 246, 1)',     // Garis Tepi Biru
                    borderWidth: 1,
                    borderRadius: 5, // Sudut batang melengkung
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4],
                            color: '#e5e7eb'
                        },
                        ticks: {
                            stepSize: 1 // Agar sumbu Y tidak menampilkan desimal (0.5 orang)
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legend jika hanya 1 data
                    }
                }
            }
        });
    </script>

@endsection