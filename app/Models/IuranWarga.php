<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class IuranWarga extends Model
{
    protected $fillable = [
        'warga_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'tanggal_bayar',
        'keterangan',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
