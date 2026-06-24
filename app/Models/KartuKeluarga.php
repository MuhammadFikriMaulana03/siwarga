<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuKeluarga extends Model
{
    protected $fillable = [
        'rt_id',
        'no_kk',
        'kepala_keluarga',
        'alamat',
        'is_active',
    ];

    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    public function wargas()
{
    return $this->hasMany(Warga::class);
}
}