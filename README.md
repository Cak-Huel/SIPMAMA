# SIPMAMA - Sistem Informasi Penerimaan Mahasiswa Magang

**SIPMAMA** adalah sebuah sistem informasi berbasis web yang dirancang untuk mengelola proses penerimaan mahasiswa magang dan memfasilitasi pencatatan kehadiran (presensi) secara digital. Aplikasi ini dibangun untuk menyederhanakan alur kerja bagi calon pendaftar dan admin instansi.

## ✨ Fitur Utama

Berdasarkan analisis kode, sistem ini memiliki fitur-fitur berikut:

### Untuk Mahasiswa (Pendaftar)
- **Pendaftaran & Otentikasi**: Registrasi akun, login, dan verifikasi email.
- **Pengajuan Formulir Magang**: Mengisi formulir pendaftaran online dengan unggah dokumen (proposal, surat rekomendasi).
- **Dasbor & Profil**: Memantau status pendaftaran (`Menunggu`, `Lolos`, `Ditolak`, `Perlu Revisi`) di halaman profil.
- **Revisi Pendaftaran**: Kemampuan untuk memperbaiki dan mengirim ulang formulir jika diminta oleh admin.
- **Sistem Presensi**: Mencatat kehadiran harian (datang & pulang) setelah diterima.
- **Pusat Informasi**: Mengakses informasi kuota magang, syarat & ketentuan, galeri kegiatan, dan FAQ.
- **Notifikasi Email**: Menerima pemberitahuan email otomatis saat status pendaftaran diperbarui.

### Untuk Admin & Operator
- **Dasbor Analitik**: Ringkasan data pendaftar harian, pendaftar menunggu verifikasi, sisa kuota bulanan, dan grafik tren pendaftaran.
- **Manajemen Pendaftar**:
    - Melihat, memfilter (berdasarkan status, periode), dan mencari data pendaftar.
    - Memverifikasi pendaftaran (`Lolos`, `Ditolak`, `Perlu Revisi`) dengan kolom catatan.
    - Mengunduh dokumen pendaftar dan mengekspor data ke format CSV.
- **Manajemen Presensi**: Memantau dan merekap data kehadiran, serta mengekspornya ke CSV.
- **Manajemen Konten Situs**:
    - Mengelola kuota magang (kuota global dan kuota khusus per periode).
    - Memperbarui halaman Syarat & Ketentuan, Poster, Galeri, dan FAQ.
- **Manajemen Hak Akses (Khusus Admin)**: Mengelola akun dengan peran 'Operator'.

## 🚀 Teknologi yang Digunakan

- **Backend**: PHP 8+ dengan Laravel
- **Frontend**: Tailwind CSS, Blade
- **Database**: MySQL / MariaDB
- **Tools**: Composer, NPM

## 🛠️ Instalasi & Konfigurasi Lokal

Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

1.  **Clone repository:**
    ```bash
    git clone [URL_REPOSITORY_ANDA] sipmama
    cd sipmama
    ```

2.  **Install dependensi PHP via Composer:**
    ```bash
    composer install
    ```

3.  **Salin file environment:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate kunci aplikasi Laravel:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database:**
    Buka file `.env` dan sesuaikan variabel `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi lokal Anda.

6.  **Jalankan migrasi database:**
    ```bash
    php artisan migrate
    ```
    *(Opsional) Jika proyek memiliki seeder, jalankan juga `php artisan db:seed`.*

7.  **Buat tautan simbolis (symlink) untuk storage:**
    ```bash
    php artisan storage:link
    ```

8.  **Install dependensi frontend & compile assets:**
    ```bash
    npm install
    npm run dev
    ```

9.  **Jalankan server development:**
    ```bash
    php artisan serve
    ```
    Aplikasi akan dapat diakses di `http://127.0.0.1:8000`.

## 📸 Tampilan Aplikasi

Berikut adalah beberapa tangkapan layar dari fitur-fitur utama aplikasi.

<details>
<summary><b>Klik untuk melihat galeri tangkapan layar</b></summary>
<br>
<table width="100%">
    <tr>
        <td align="center"><b>Dasbor Pengguna</b><br><img src="screenshoot/dashboard.png" alt="Dashboard Pengguna"></td>
        <td align="center"><b>Halaman Informasi</b><br><img src="screenshoot/informasi.png" alt="Halaman Informasi"></td>
    </tr>
    <tr>
        <td align="center"><b>Halaman Galeri</b><br><img src="screenshoot/galeri.png" alt="Halaman Galeri"></td>
        <td align="center"><b>Halaman FAQ</b><br><img src="screenshoot/FAQ.png" alt="Halaman FAQ"></td>
    </tr>
    <tr>
        <td align="center"><b>Halaman Login</b><br><img src="screenshoot/login.png" alt="Halaman Login"></td>
        <td align="center"><b>Halaman Daftar Akun</b><br><img src="screenshoot/daftar-akun.png" alt="Halaman Daftar Akun"></td>
    </tr>
    <tr>
        <td align="center"><b>Profil Pengguna</b><br><img src="screenshoot/profil.png" alt="Profil Pengguna"></td>
        <td align="center"><b>Halaman Formulir</b><br><img src="screenshoot/formulir.png" alt="Halaman Formulir"></td>
    </tr>
    <tr>
        <td align="center"><b>Halaman Presensi</b><br><img src="screenshoot/presensi.png" alt="Halaman Presensi"></td>
        <td align="center"><b>Dashboard Admin</b><br><img src="screenshoot/dashboard-admin.png" alt="Dashboard Admin"></td>
    </tr>
    <tr>
        <td align="center"><b>Halaman Pendaftar</b><br><img src="screenshoot/kelola-pendaftar.png" alt="Halaman Pendaftar"></td>
        <td align="center"><b>Halaman Verifikasi</b><br><img src="screenshoot/verifikasi.png" alt="Halaman Verifikasi"></td>
    </tr>
    <tr>
        <td align="center"><b>Halaman Kelola Informasi</b><br><img src="screenshoot/kelola-informasi.png" alt="Halaman Kelola Informasi"></td>
        <td align="center"><b>Halaman Kelola Kuota</b><br><img src="screenshoot/kelola-kuota.png" alt="Halaman Kelola Kuota"></td>
    </tr>
    <tr>
        <td align="center"><b>Halaman Kelola Galeri</b><br><img src="screenshoot/kelola-galeri.png" alt="Halaman Kelola Galeri"></td>
        <td align="center"><b>Halaman Kelola FAQ</b><br><img src="screenshoot/kelola-FAQ.png" alt="Halaman Kelola FAq"></td>
    </tr>
    <tr>
        <td align="center"><b>Data Presensi</b><br><img src="screenshoot/data-presensi.png" alt="Data Presensi"></td>
        <td align="center"><b>Halaman Kelola Operator</b><br><img src="screenshoot/kelola-operator.png" alt="Halaman Kelola Operator"></td>
    </tr>
    <tr>
        <td align="center"><b>Profil Admin</b><br><img src="screenshoot/profil-admin.png" alt="Profil Admin"></td>
    </tr>
</table>
</details>


=======================================================================
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
