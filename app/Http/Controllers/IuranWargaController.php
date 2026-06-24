<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\IuranWarga;
use App\Models\KasTransaksi;
use App\Models\Warga;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class IuranWargaController extends Controller
{

    private function namaBulan(int|string $bulan, int|string $tahun): string
{
    return Carbon::createFromDate((int) $tahun, (int) $bulan, 1)
        ->translatedFormat('F');
}

private function syncKasIuran(IuranWarga $iuranWarga): void
{
    $iuranWarga->load('warga');

    $keteranganAuto = 'Auto dari iuran warga ID: ' . $iuranWarga->id;

    if ($iuranWarga->status !== 'lunas') {
        KasTransaksi::where('kategori', 'Iuran Warga')
            ->where('keterangan', 'like', '%' . $keteranganAuto . '%')
            ->delete();

        return;
    }

    KasTransaksi::updateOrCreate(
        [
            'kategori' => 'Iuran Warga',
            'keterangan' => $keteranganAuto,
        ],
        [
            'tanggal' => $iuranWarga->tanggal_bayar ?? now()->toDateString(),
            'tipe' => 'masuk',
            'judul' => 'Iuran ' . $iuranWarga->warga->nama . ' '
                . $this->namaBulan($iuranWarga->bulan, $iuranWarga->tahun)
                . ' ' . $iuranWarga->tahun,
            'nominal' => $iuranWarga->nominal,
        ]
    );
}
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $status = $request->get('status');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $iuranWargas = IuranWarga::with(['warga.rt', 'warga.kartuKeluarga'])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('warga', function ($wargaQuery) use ($search) {
                    $wargaQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('no_kk', 'like', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($bulan !== null && $bulan !== '', function ($query) use ($bulan) {
                $query->where('bulan', $bulan);
            })
            ->when($tahun !== null && $tahun !== '', function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalTagihan = IuranWarga::sum('nominal');
        $totalLunas = IuranWarga::where('status', 'lunas')->sum('nominal');
        $totalBelumBayar = IuranWarga::where('status', 'belum_bayar')->sum('nominal');

        return view('admin.iuran-wargas.index', compact(
            'iuranWargas',
            'totalTagihan',
            'totalLunas',
            'totalBelumBayar',
            'search',
            'status',
            'bulan',
            'tahun'
        ));
    }

    public function create()
{
    $wargas = Warga::with(['rt', 'kartuKeluarga'])
        ->where('is_active', true)
        ->orderBy('nama')
        ->get();

    return view('admin.iuran-wargas.create', compact('wargas'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'warga_id' => ['required', 'exists:wargas,id'],
        'bulan' => ['required', 'integer', 'min:1', 'max:12'],
        'tahun' => ['required', 'integer', 'min:2000'],
        'nominal' => ['required', 'numeric', 'min:0'],
        'status' => ['required', 'in:belum_bayar,lunas'],
        'tanggal_bayar' => ['nullable', 'date'],
        'keterangan' => ['nullable', 'string'],
    ]);

    $exists = IuranWarga::where('warga_id', $validated['warga_id'])
        ->where('bulan', $validated['bulan'])
        ->where('tahun', $validated['tahun'])
        ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->with('error', 'Iuran warga untuk bulan dan tahun tersebut sudah ada.');
    }

    if ($validated['status'] === 'lunas' && empty($validated['tanggal_bayar'])) {
        $validated['tanggal_bayar'] = now()->toDateString();
    }

    $iuranWarga = IuranWarga::create($validated);

    $iuranWarga->load('warga');

    ActivityLog::record(
        'Iuran Warga',
        'Tambah Iuran',
        'Menambahkan iuran untuk ' . ($iuranWarga->warga->nama ?? '-') . ' periode ' . $iuranWarga->bulan . '/' . $iuranWarga->tahun
    );

    $this->syncKasIuran($iuranWarga);

    return redirect()
        ->route('admin.iuran-wargas.index')
        ->with('success', 'Data iuran warga berhasil ditambahkan.');
}

public function edit(IuranWarga $iuranWarga)
{
    $wargas = Warga::with(['rt', 'kartuKeluarga'])
        ->where('is_active', true)
        ->orderBy('nama')
        ->get();

    return view('admin.iuran-wargas.edit', compact('iuranWarga', 'wargas'));
}

public function update(Request $request, IuranWarga $iuranWarga)
{
    $validated = $request->validate([
        'warga_id' => ['required', 'exists:wargas,id'],
        'bulan' => ['required', 'integer', 'min:1', 'max:12'],
        'tahun' => ['required', 'integer', 'min:2000'],
        'nominal' => ['required', 'numeric', 'min:0'],
        'status' => ['required', 'in:belum_bayar,lunas'],
        'tanggal_bayar' => ['nullable', 'date'],
        'keterangan' => ['nullable', 'string'],
    ]);

    $exists = IuranWarga::where('warga_id', $validated['warga_id'])
        ->where('bulan', $validated['bulan'])
        ->where('tahun', $validated['tahun'])
        ->where('id', '!=', $iuranWarga->id)
        ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->with('error', 'Iuran warga untuk bulan dan tahun tersebut sudah ada.');
    }

    $statusSebelumnya = $iuranWarga->status;

    if ($validated['status'] === 'lunas' && empty($validated['tanggal_bayar'])) {
        $validated['tanggal_bayar'] = now()->toDateString();
    }

    if ($validated['status'] === 'belum_bayar') {
        $validated['tanggal_bayar'] = null;
    }

    $iuranWarga->update($validated);

    $this->syncKasIuran($iuranWarga);

    $iuranWarga->load('warga');

    ActivityLog::record(
        'Iuran Warga',
        'Edit Iuran',
        'Mengubah iuran untuk ' . ($iuranWarga->warga->nama ?? '-') . ' periode ' . $iuranWarga->bulan . '/' . $iuranWarga->tahun
    );

    return redirect()
        ->route('admin.iuran-wargas.index')
        ->with('success', 'Data iuran warga berhasil diperbarui.');
}

    public function generateForm()
{
    return view('admin.iuran-wargas.generate');
}

public function generate(Request $request)
{
    $validated = $request->validate([
        'bulan' => ['required', 'integer', 'min:1', 'max:12'],
        'tahun' => ['required', 'integer', 'min:2000'],
        'nominal' => ['required', 'numeric', 'min:0'],
        'keterangan' => ['nullable', 'string'],
    ]);

    $wargas = Warga::where('is_active', true)->get();

    $created = 0;
    $skipped = 0;

    foreach ($wargas as $warga) {
        $exists = IuranWarga::where('warga_id', $warga->id)
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            $skipped++;
            continue;
        }

        IuranWarga::create([
            'warga_id' => $warga->id,
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'nominal' => $validated['nominal'],
            'status' => 'belum_bayar',
            'tanggal_bayar' => null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $created++;
    }

    ActivityLog::record(
        'Iuran Warga',
        'Generate Massal',
        'Generate iuran massal periode ' . $request->bulan . '/' . $request->tahun
    );

    return redirect()
        ->route('admin.iuran-wargas.index', [
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
        ])
        ->with('success', "Generate iuran selesai. Dibuat: {$created}, Dilewati: {$skipped}.");
}

    public function tandaiLunas(IuranWarga $iuranWarga)
{
    if ($iuranWarga->status === 'lunas') {
        return redirect()
            ->route('admin.iuran-wargas.index')
            ->with('success', 'Iuran warga sudah lunas.');
    }

    $iuranWarga->update([
        'status' => 'lunas',
        'tanggal_bayar' => now()->toDateString(),
    ]);

    $this->syncKasIuran($iuranWarga);

    $iuranWarga->load('warga');

    ActivityLog::record(
        'Iuran Warga',
        'Tandai Lunas',
        'Menandai lunas iuran ' . ($iuranWarga->warga->nama ?? '-') . ' periode ' . $iuranWarga->bulan . '/' . $iuranWarga->tahun
    );

    return redirect()
        ->route('admin.iuran-wargas.index', request()->query())
        ->with('success', 'Iuran berhasil ditandai lunas dan masuk ke kas.');
}

    public function exportPdf(Request $request)
{
    $search = trim($request->get('search', ''));
    $status = $request->get('status');
    $bulan = $request->get('bulan');
    $tahun = $request->get('tahun');

    $iuranWargas = IuranWarga::with(['warga.rt', 'warga.kartuKeluarga'])
        ->when($search !== '', function ($query) use ($search) {
            $query->whereHas('warga', function ($wargaQuery) use ($search) {
                $wargaQuery->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('kartuKeluarga', function ($kkQuery) use ($search) {
                        $kkQuery->where('no_kk', 'like', "%{$search}%")
                            ->orWhere('kepala_keluarga', 'like', "%{$search}%");
                    });
            });
        })
        ->when($status !== null && $status !== '', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($bulan !== null && $bulan !== '', function ($query) use ($bulan) {
            $query->where('bulan', $bulan);
        })
        ->when($tahun !== null && $tahun !== '', function ($query) use ($tahun) {
            $query->where('tahun', $tahun);
        })
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();

    $totalTagihan = $iuranWargas->sum('nominal');
    $totalLunas = $iuranWargas->where('status', 'lunas')->sum('nominal');
    $totalBelumBayar = $iuranWargas->where('status', 'belum_bayar')->sum('nominal');

    $pdf = Pdf::loadView('admin.iuran-wargas.pdf', compact(
        'iuranWargas',
        'totalTagihan',
        'totalLunas',
        'totalBelumBayar',
        'search',
        'status',
        'bulan',
        'tahun'
    ))->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-iuran-warga-' . now()->format('Y-m-d-His') . '.pdf');
}

    public function export(Request $request)
{
    $search = trim($request->get('search', ''));
    $status = $request->get('status');
    $bulan = $request->get('bulan');
    $tahun = $request->get('tahun');

    $fileName = 'laporan-iuran-warga-' . now()->format('Y-m-d-His') . '.csv';

    $iuranWargas = IuranWarga::with(['warga.rt', 'warga.kartuKeluarga'])
        ->when($search !== '', function ($query) use ($search) {
            $query->whereHas('warga', function ($wargaQuery) use ($search) {
                $wargaQuery->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('kartuKeluarga', function ($kkQuery) use ($search) {
                        $kkQuery->where('no_kk', 'like', "%{$search}%")
                            ->orWhere('kepala_keluarga', 'like', "%{$search}%");
                    });
            });
        })
        ->when($status !== null && $status !== '', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($bulan !== null && $bulan !== '', function ($query) use ($bulan) {
            $query->where('bulan', $bulan);
        })
        ->when($tahun !== null && $tahun !== '', function ($query) use ($tahun) {
            $query->where('tahun', $tahun);
        })
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();

    $totalTagihan = $iuranWargas->sum('nominal');
    $totalLunas = $iuranWargas->where('status', 'lunas')->sum('nominal');
    $totalBelumBayar = $iuranWargas->where('status', 'belum_bayar')->sum('nominal');

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $callback = function () use ($iuranWargas, $totalTagihan, $totalLunas, $totalBelumBayar, $bulan, $tahun) {
        $file = fopen('php://output', 'w');

        // BOM UTF-8 supaya Excel Indonesia aman
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        $delimiter = ';';

        $namaBulan = $bulan
            ? \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F')
            : null;

        $periode = $namaBulan || $tahun
            ? trim(($namaBulan ?? 'Semua Bulan') . ' ' . ($tahun ?? ''))
            : 'Semua Periode';

        fputcsv($file, ['Laporan Iuran Warga'], $delimiter);
        fputcsv($file, ['Periode', $periode], $delimiter);
        fputcsv($file, ['Tanggal Export', now()->format('Y-m-d H:i:s')], $delimiter);
        fputcsv($file, [], $delimiter);

        fputcsv($file, ['Ringkasan'], $delimiter);
        fputcsv($file, ['Total Tagihan', 'Rp ' . number_format($totalTagihan, 0, ',', '.')], $delimiter);
        fputcsv($file, ['Sudah Lunas', 'Rp ' . number_format($totalLunas, 0, ',', '.')], $delimiter);
        fputcsv($file, ['Belum Bayar', 'Rp ' . number_format($totalBelumBayar, 0, ',', '.')], $delimiter);
        fputcsv($file, [], $delimiter);

        fputcsv($file, [
            'No',
            'Nama Warga',
            'NIK',
            'No KK',
            'RT',
            'Periode',
            'Nominal',
            'Status',
            'Tanggal Bayar',
            'Keterangan',
        ], $delimiter);

        foreach ($iuranWargas as $index => $item) {
            fputcsv($file, [
                $index + 1,
                $item->warga->nama ?? '-',
                "\t" . ($item->warga->nik ?? '-'),
                "\t" . ($item->warga->kartuKeluarga->no_kk ?? $item->warga->no_kk ?? '-'),
                'RT ' . ($item->warga->rt->nomor_rt ?? '-'),
                \Carbon\Carbon::create()->month((int) $item->bulan)->translatedFormat('F') . ' ' . $item->tahun,
                $item->nominal,
                $item->status === 'lunas' ? 'Lunas' : 'Belum Bayar',
                $item->tanggal_bayar ?? '-',
                $item->keterangan ?? '-',
            ], $delimiter);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    public function destroy(IuranWarga $iuranWarga)
    {
        $iuranWarga->delete();

        return redirect()
            ->route('admin.iuran-wargas.index')
            ->with('success', 'Data iuran berhasil dihapus.');
    }
}
