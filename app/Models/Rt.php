<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rt extends Model
{
    protected $fillable = [
        'nomor_rt',
        'nama_ketua_rt',
        'no_hp',
        'alamat_sekretariat',
        'is_active',
    ];

    public function wargas()
    {
        return $this->hasMany(Warga::class);
    }
}
