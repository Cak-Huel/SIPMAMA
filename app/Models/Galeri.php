<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeris';
    protected $fillable = ['judul', 'periode', 'foto_path', 'deskripsi'];
}
