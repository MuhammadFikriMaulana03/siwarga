<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    public static function setValue(string $key, ?string $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function allSettings(): array
    {
        return [
            'rt_rw_name' => self::getValue('rt_rw_name', config('siwarga.rt_rw_name')),
            'kelurahan' => self::getValue('kelurahan', config('siwarga.kelurahan')),
            'kecamatan' => self::getValue('kecamatan', config('siwarga.kecamatan')),
            'kota' => self::getValue('kota', config('siwarga.kota')),
            'alamat_sekretariat' => self::getValue('alamat_sekretariat', config('siwarga.alamat_sekretariat')),
            'no_hp_rw' => self::getValue('no_hp_rw', config('siwarga.no_hp_rw')),
            'ketua_rw' => self::getValue('ketua_rw', config('siwarga.ketua_rw')),
        ];
    }
}
