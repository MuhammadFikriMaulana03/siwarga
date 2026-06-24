<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'kode_tracking',
        'rt_id',
        'nama',
        'no_hp',
        'judul',
        'isi',
        'foto',
        'status',
        'tanggapan',
    ];

    public function rt()
{
    return $this->belongsTo(\App\Models\Rt::class);
}
}