<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pengaturan';

    // Kolom yang boleh diisi (Mass Assignable)
    protected $fillable = [
        'key',          // Kunci unik (misal: 'kuota_global', 'syarat_ketentuan')
        'judul',        // Judul pengaturan
        'isi_teks',     // Isi (text/angka)
        'file_poster',  // Path file (jika ada)
    ];
}
