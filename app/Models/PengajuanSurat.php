<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $fillable = [
        'jenis_surat_id',
        'warga_id',
        'nama_pemohon',
        'nik',
        'no_hp',
        'alamat',
        'keperluan',
        'status',
        'catatan_admin',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
