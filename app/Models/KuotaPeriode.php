<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuotaPeriode extends Model
{
    protected $fillable = ['bulan', 'tahun', 'kuota'];
    public $timestamps = true;
}
