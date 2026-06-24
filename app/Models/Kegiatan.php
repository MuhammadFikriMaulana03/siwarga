<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Kegiatan extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'lokasi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'gambar',
        'status',
    ];
}
