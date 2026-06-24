<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\Warga;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index(Request $request)
{
    $search = trim($request->get('search', ''));
    $statusWarga = $request->get('status_warga');
    $statusAktif = $request->get('status_aktif');

    $wargas = Warga::with(['rt', 'kartuKeluarga'])
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('kartuKeluarga', function ($kkQuery) use ($search) {
                        $kkQuery->where('no_kk', 'like', "%{$search}%")
                            ->orWhere('kepala_keluarga', 'like', "%{$search}%");
                    })
                    ->orWhereHas('rt', function ($rtQuery) use ($search) {
                        $rtQuery->where('nomor_rt', 'like', "%{$search}%");
                    });
            });
        })
        ->when($statusWarga !== null && $statusWarga !== '', function ($query) use ($statusWarga) {
            $query->where('status_warga', $statusWarga);
        })
        ->when($statusAktif !== null && $statusAktif !== '', function ($query) use ($statusAktif) {
            $query->where('is_active', (bool) $statusAktif);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.wargas.index', compact(
        'wargas',
        'search',
        'statusWarga',
        'statusAktif'
    ));
}

    public function export(Request $request)
{
    $search = trim($request->get('search', ''));
    $statusWarga = $request->get('status_warga');
    $statusAktif = $request->get('status_aktif');

    $fileName = 'data-warga-' . now()->format('Y-m-d-His') . '.csv';

    $wargas = Warga::with(['rt', 'kartuKeluarga'])
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('kartuKeluarga', function ($kkQuery) use ($search) {
                        $kkQuery->where('no_kk', 'like', "%{$search}%")
                            ->orWhere('kepala_keluarga', 'like', "%{$search}%");
                    })
                    ->orWhereHas('rt', function ($rtQuery) use ($search) {
                        $rtQuery->where('nomor_rt', 'like', "%{$search}%");
                    });
            });
        })
        ->when($statusWarga !== null && $statusWarga !== '', function ($query) use ($statusWarga) {
            $query->where('status_warga', $statusWarga);
        })
        ->when($statusAktif !== null && $statusAktif !== '', function ($query) use ($statusAktif) {
            $query->where('is_active', (bool) $statusAktif);
        })
        ->orderBy('nama')
        ->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $callback = function () use ($wargas) {
        $file = fopen('php://output', 'w');

        // BOM UTF-8 supaya Excel baca karakter Indonesia dengan benar
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        $delimiter = ';';

        fputcsv($file, [
            'No',
            'NIK',
            'No KK',
            'Nama',
            'RT',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Agama',
            'Pekerjaan',
            'No HP',
            'Status Warga',
            'Status Aktif',
        ], $delimiter);

        foreach ($wargas as $index => $warga) {
            fputcsv($file, [
                $index + 1,
                "\t" . $warga->nik,
                "\t" . ($warga->kartuKeluarga->no_kk ?? $warga->no_kk ?? '-'),
                $warga->nama,
                "\tRT " . ($warga->rt->nomor_rt ?? '-'),
                $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                $warga->tempat_lahir ?? '-',
                $warga->tanggal_lahir ?? '-',
                $warga->alamat ?? '-',
                $warga->agama ?? '-',
                $warga->pekerjaan ?? '-',
                "\t" . ($warga->no_hp ?? '-'),
                ucfirst($warga->status_warga),
                $warga->is_active ? 'Aktif' : 'Nonaktif',
            ], $delimiter);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    public function importForm()
{
    return view('admin.wargas.import');
}

public function import(Request $request)
{
    $request->validate([
        'file' => ['required', 'file', 'mimes:csv,txt'],
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getRealPath(), 'r');

    if (!$handle) {
        return redirect()
            ->route('admin.wargas.import.form')
            ->with('error', 'File tidak bisa dibaca.');
    }

    $delimiter = ';';
    $header = fgetcsv($handle, 1000, $delimiter);

    $imported = 0;
    $updated = 0;
    $skipped = 0;

    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
        if (count($row) < 14) {
            $skipped++;
            continue;
        }

        $nik = trim(str_replace("\t", '', $row[1] ?? ''));
        $noKk = trim(str_replace("\t", '', $row[2] ?? ''));
        $nama = trim($row[3] ?? '');
        $rtNomor = trim(str_replace(['RT', 'rt'], '', $row[4] ?? ''));
        $jenisKelamin = trim($row[5] ?? '');
        $tempatLahir = trim($row[6] ?? '');
        $tanggalLahir = trim($row[7] ?? '');
        $alamat = trim($row[8] ?? '');
        $agama = trim($row[9] ?? '');
        $pekerjaan = trim($row[10] ?? '');
        $noHp = trim(str_replace("\t", '', $row[11] ?? ''));
        $statusWarga = strtolower(trim($row[12] ?? 'tetap'));
        $statusAktif = strtolower(trim($row[13] ?? 'aktif'));

        if ($nik === '' || $nama === '' || $rtNomor === '') {
            $skipped++;
            continue;
        }

        $rt = Rt::firstOrCreate(
            ['nomor_rt' => $rtNomor],
            [
                'nama_ketua_rt' => null,
                'no_hp' => null,
                'alamat_sekretariat' => null,
                'is_active' => true,
            ]
        );

        $kartuKeluarga = null;

        if ($noKk !== '' && $noKk !== '-') {
            $kartuKeluarga = KartuKeluarga::firstOrCreate(
                ['no_kk' => $noKk],
                [
                    'rt_id' => $rt->id,
                    'kepala_keluarga' => $nama,
                    'alamat' => $alamat,
                    'is_active' => true,
                ]
            );
        }

        $jk = str_contains(strtolower($jenisKelamin), 'perempuan') ? 'P' : 'L';

        if (!in_array($statusWarga, ['tetap', 'kontrak', 'pendatang'])) {
            $statusWarga = 'tetap';
        }

        $isActive = $statusAktif === 'aktif';

        $existing = Warga::where('nik', $nik)->first();

        Warga::updateOrCreate(
            ['nik' => $nik],
            [
                'rt_id' => $rt->id,
                'kartu_keluarga_id' => $kartuKeluarga?->id,
                'no_kk' => $noKk,
                'nama' => $nama,
                'tempat_lahir' => $tempatLahir ?: null,
                'tanggal_lahir' => $tanggalLahir ?: null,
                'jenis_kelamin' => $jk,
                'alamat' => $alamat ?: null,
                'agama' => $agama ?: null,
                'pekerjaan' => $pekerjaan ?: null,
                'no_hp' => $noHp ?: null,
                'status_warga' => $statusWarga,
                'is_active' => $isActive,
            ]
        );

        if ($existing) {
            $updated++;
        } else {
            $imported++;
        }
    }

    fclose($handle);

    return redirect()
        ->route('admin.wargas.index')
        ->with('success', "Import selesai. Baru: {$imported}, Update: {$updated}, Dilewati: {$skipped}.");
}

    public function create()
{
    $rts = Rt::where('is_active', true)->orderBy('nomor_rt')->get();
    $kartuKeluargas = KartuKeluarga::with('rt')
    ->where('is_active', true)
    ->orderBy('no_kk')
    ->get();

    return view('admin.wargas.create', compact('rts', 'kartuKeluargas'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rt_id' => ['required', 'exists:rts,id'],
            'kartu_keluarga_id' => ['nullable', 'exists:kartu_keluargas,id'],
            'nik' => ['required', 'string', 'max:20', 'unique:wargas,nik'],
            'no_kk' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'agama' => ['nullable', 'in:Islam,Kristen Protestan,Katolik,Hindu,Buddha,Konghucu,Lainnya'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'status_warga' => ['required', 'in:tetap,kontrak,pendatang'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Warga::create($validated);

        return redirect()
            ->route('admin.wargas.index')
            ->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function edit(Warga $warga)
{
    $rts = Rt::where('is_active', true)->orderBy('nomor_rt')->get();
    $kartuKeluargas = KartuKeluarga::with('rt')
    ->where('is_active', true)
    ->orderBy('no_kk')
    ->get();

    return view('admin.wargas.edit', compact('warga', 'rts', 'kartuKeluargas'));
}

    public function update(Request $request, Warga $warga)
    {
        $validated = $request->validate([
            'rt_id' => ['required', 'exists:rts,id'],
            'kartu_keluarga_id' => ['nullable', 'exists:kartu_keluargas,id'],
            'nik' => ['required', 'string', 'max:20', 'unique:wargas,nik,' . $warga->id],
            'no_kk' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'agama' => ['nullable', 'string', 'max:50'],
            'agama_lainnya' => ['nullable', 'required_if:agama,Lainnya', 'string', 'max:100'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'status_warga' => ['required', 'in:tetap,kontrak,pendatang'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (($validated['agama'] ?? null) === 'Lainnya') {
            $validated['agama'] = $validated['agama_lainnya'];
        }

        unset($validated['agama_lainnya']);

        $validated['is_active'] = $request->has('is_active');

        $warga->update($validated);

        return redirect()
            ->route('admin.wargas.index')
            ->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();

        return redirect()
            ->route('admin.wargas.index')
            ->with('success', 'Data warga berhasil dihapus.');
    }
}
