<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    // Beri tahu Eloquent nama tabel kita
    protected $table = 'pendaftar';

    // Beri tahu Eloquent nama Primary Key kita
    protected $primaryKey = 'id_pendaftar';

    /**
     * Kolom-kolom yang boleh diisi secara massal
     * (WAJIB untuk create())
     */
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nim',
        'universitas',
        'jurusan',
        'tgl_start',
        'tgl_end',
        'wa',
        'nik',
        'address',
        'proposal',
        'rekom',
        'status',
        'catatan',
        'verified_by',
        'verified_at',
    ];

    /**
     * Relasi ke model User
     * (Satu pendaftar dimiliki oleh satu user)
     */
    public function user()
    {
        // (Nama model, foreign key di tabel 'pendaftar', primary key di tabel 'users')
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
