<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Umkm extends Model
{
    protected $fillable = [
        'warga_id',
        'nama_usaha',
        'pemilik',
        'kategori',
        'deskripsi',
        'no_hp',
        'alamat',
        'foto',
        'status',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
