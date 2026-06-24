<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pengumuman extends Model
{
    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'status',
        'tanggal_publish',
    ];
}
