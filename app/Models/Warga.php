<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Warga extends Model
{
    protected $fillable = [
        'rt_id',
        'kartu_keluarga_id',
        'nik',
        'no_kk',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'agama',
        'pekerjaan',
        'no_hp',
        'status_warga',
        'is_active',
    ];

    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    public function kartuKeluarga()
{
    return $this->belongsTo(KartuKeluarga::class);
}
}
