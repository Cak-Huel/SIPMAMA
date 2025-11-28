<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

// UNTUK LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// DASHBOARD
Route::get('/dashboard', [PageController::class, 'index'])->name('dashboard');
Route::get('/', [PageController::class, 'index'])->name('home');

// HALAMAN FORMULIR PENDAFTARAN (HANYA BISA DIAKSES JIKA SUDAH LOGIN)
Route::get('/formulir-pendaftaran', [FormulirController::class, 'showForm'])
    ->middleware('auth')
    ->name('pendaftaran.form');
Route::post('/formulir-pendaftaran', [FormulirController::class, 'submitForm'])
    ->middleware('auth')
    ->name('pendaftaran.submit');

// UNTUK LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// HALAMAN INFORMASI
Route::get('/informasi', [PageController::class, 'informasi'])->name('informasi');

// HALAMAN PROFIL USER
Route::middleware('auth')->group(function () {
    // 1. Menampilkan halaman profil
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil.show');
    // 2. Menerima file 'rekomendasi' yang di-upload dari profil
    Route::post('/profil/upload-rekom', [ProfileController::class, 'uploadRekom'])->name('profil.rekom.upload');
    // 3. Menghapus akun
    Route::post('/profil/delete', [ProfileController::class, 'destroy'])->name('profil.destroy');
    // presensi routes
    // PRESENSI
    Route::get('/presensi', [\App\Http\Controllers\PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [\App\Http\Controllers\PresensiController::class, 'store'])->name('presensi.store');
});

// HALAMAN ADMIN (HANYA BISA DIAKSES OLEH ADMIN)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    // URL: /admin/dashboard
    // Nama Rute: admin.dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // MENU PENDAFTAR
    Route::get('/pendaftar', [AdminController::class, 'pendaftar'])->name('pendaftar.index');

    // AKSI DOWNLOAD
    Route::get('/pendaftar/{id}/download/{jenis}', [AdminController::class, 'downloadDokumen'])->name('pendaftar.download');

    // AKSI EKSPOR
    Route::get('/pendaftar/export', [AdminController::class, 'exportCsv'])->name('pendaftar.export');

    // HALAMAN VERIFIKASI
    Route::get('/pendaftar/{id}', [AdminController::class, 'show'])->name('pendaftar.show');

    // UPDATE STATUS (AKSI)
    Route::patch('/pendaftar/{id}/update', [AdminController::class, 'updateStatus'])->name('pendaftar.update');

    // PREVIEW PDF
    Route::get('/pendaftar/{id}/preview/{jenis}', [AdminController::class, 'previewDokumen'])->name('pendaftar.preview');

    // Di dalam group admin:
    Route::get('/informasi', [\App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');

    // RUTE BARU: Update Kuota Global
    Route::post('/informasi/kuota/update', [\App\Http\Controllers\InformasiController::class, 'updateKuotaGlobal'])->name('informasi.kuota.update');

    // Rute Konten Statis (Tetap)
    Route::post('/informasi/konten/update', [\App\Http\Controllers\InformasiController::class, 'updateKonten'])->name('informasi.konten.update');

    // MENU PRESENSI
    Route::get('/presensi', [AdminController::class, 'presensi'])->name('presensi.index');
    Route::get('/presensi/{id}/download', [AdminController::class, 'downloadBukti'])->name('presensi.download');

    // EKSPOR PRESENSI
    Route::get('/presensi/export', [AdminController::class, 'exportPresensiCsv'])->name('presensi.export');
});
