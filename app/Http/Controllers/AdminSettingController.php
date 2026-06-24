<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function edit()
    {
        $settings = SystemSetting::allSettings();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'rt_rw_name' => ['required', 'string', 'max:255'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'kota' => ['required', 'string', 'max:255'],
            'alamat_sekretariat' => ['required', 'string'],
            'no_hp_rw' => ['nullable', 'string', 'max:30'],
            'ketua_rw' => ['required', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::setValue($key, $value);
        }

        ActivityLog::record(
        'Pengaturan',
        'Update Pengaturan',
        'Admin memperbarui pengaturan sistem dan kop surat.'
        );

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}