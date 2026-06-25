<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'jumlah',
        'satuan',
        'lokasi',
        'kondisi',
        'tanggal_beli',
        'harga_beli',
        'foto',
        'keterangan'
    ];
}