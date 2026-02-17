<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\AdminProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Rute Publik (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/informasi', [PageController::class, 'informasi'])->name('informasi');
Route::get('/galeri', [PageController::class, 'showGaleri'])->name('galeri.index');
Route::get('/faq', [PageController::class, 'showFaq'])->name('faq.index');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi & Verifikasi Email
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Batasi 3 kali percobaan per menit untuk IP yang sama
Route::middleware('throttle:3,1')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Alur Verifikasi Email
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Tautan verifikasi baru telah dikirim ke email Anda!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Rute Pengguna (Wajib Login & Verifikasi Email)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'index'])->name('dashboard');
    // Formulir Pendaftaran
    Route::get('/formulir-pendaftaran', [FormulirController::class, 'showForm'])->name('pendaftaran.form');
    Route::post('/formulir-pendaftaran', [FormulirController::class, 'submitForm'])->name('pendaftaran.submit');
    Route::get('/formulir-revisi', [FormulirController::class, 'editForm'])->name('pendaftaran.edit');
    Route::put('/formulir-revisi', [FormulirController::class, 'updateForm'])->name('pendaftaran.update');
    Route::get('/preview/{jenis}', [FormulirController::class, 'previewDokumen'])->name('pendaftaran.preview');

    // Profil Pengguna
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil.show');
    Route::post('/profil/upload-proposal', [ProfileController::class, 'UploadProposal'])->name('profil.proposal.upload');
    Route::post('/profil/delete', [ProfileController::class, 'destroy'])->name('profil.destroy');

    // PRESENSI
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
});

/*
|--------------------------------------------------------------------------
| Rute Panel Admin (Wajib Login & Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Kelola Pendaftar
    Route::get('/pendaftar', [AdminController::class, 'pendaftar'])->name('pendaftar.index');
    Route::get('/pendaftar/export', [AdminController::class, 'exportCsv'])->name('pendaftar.export');
    Route::get('/pendaftar/{id}', [AdminController::class, 'show'])->name('pendaftar.show');
    Route::patch('/pendaftar/{id}/update', [AdminController::class, 'updateStatus'])->name('pendaftar.update');
    Route::get('/pendaftar/{id}/download/{jenis}', [AdminController::class, 'downloadDokumen'])->name('pendaftar.download');
    Route::get('/pendaftar/{id}/preview/{jenis}', [AdminController::class, 'previewDokumen'])->name('pendaftar.preview');

    // Kelola Informasi & Konten
    Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
    Route::post('/informasi/kuota/global', [InformasiController::class, 'updateKuotaGlobal'])->name('informasi.kuota.global');
    Route::post('/informasi/kuota/periode', [InformasiController::class, 'updateKuotaPeriode'])->name('informasi.kuota.periode');
    Route::post('/informasi/konten/update', [InformasiController::class, 'updateKonten'])->name('informasi.konten.update');
    Route::post('/informasi/faq/store', [InformasiController::class, 'storeFaq'])->name('informasi.faq.store');
    Route::delete('/informasi/faq/{id}', [InformasiController::class, 'destroyFaq'])->name('informasi.faq.destroy');
    Route::post('/informasi/galeri/store', [InformasiController::class, 'storeGaleri'])->name('informasi.galeri.store');
    Route::delete('/informasi/galeri/{id}', [InformasiController::class, 'destroyGaleri'])->name('informasi.galeri.destroy');

    // Kelola Presensi
    Route::get('/presensi', [AdminController::class, 'presensi'])->name('presensi.index');
    Route::get('/presensi/export', [AdminController::class, 'exportPresensiCsv'])->name('presensi.export');
    Route::get('/presensi/{id}/download', [AdminController::class, 'downloadBukti'])->name('presensi.download');

    // Kelola Pengguna (Operator)
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

    // Profil Admin
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
});
