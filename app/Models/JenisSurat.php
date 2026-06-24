<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    public function pengajuanSurats()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
