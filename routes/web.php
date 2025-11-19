<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FormulirController;
use app\Http\Controllers\ProfileController;

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
// 1. Menampilkan halaman profil
Route::get('/profil', [ProfileController::class, 'show'])->name('profil.show');
// 2. Menerima file 'rekomendasi' yang di-upload dari profil
Route::post('/profil/upload-rekom', [ProfileController::class, 'uploadRekom'])->name('profil.rekom.upload');
// 3. Menghapus akun
Route::post('/profil/delete', [ProfileController::class, 'destroy'])->name('profil.destroy');
