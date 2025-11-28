<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'tanggal',
        'jam',
        'jenis',
        'latitude',
        'longitude',
        'keterangan',
        'bukti_file'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id', 'id_pendaftar');
    }
}
