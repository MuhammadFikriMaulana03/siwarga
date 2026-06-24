<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class KasTransaksi extends Model
{
    protected $fillable = [
        'tanggal',
        'tipe',
        'kategori',
        'judul',
        'keterangan',
        'nominal',
    ];
}
