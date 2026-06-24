<?php

namespace App\Http\Controllers;

use App\Models\KasTransaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KasTransaksiController extends Controller
{
    public function index(Request $request)
{
    $search = trim($request->get('search', ''));
    $tipe = $request->get('tipe');
    $bulan = $request->get('bulan');
    $tahun = $request->get('tahun');

    $query = KasTransaksi::query()
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        })
        ->when($tipe !== null && $tipe !== '', function ($query) use ($tipe) {
            $query->where('tipe', $tipe);
        })
        ->when($bulan !== null && $bulan !== '', function ($query) use ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        })
        ->when($tahun !== null && $tahun !== '', function ($query) use ($tahun) {
            $query->whereYear('tanggal', $tahun);
        });

    $totalMasuk = (clone $query)->where('tipe', 'masuk')->sum('nominal');
    $totalKeluar = (clone $query)->where('tipe', 'keluar')->sum('nominal');
    $saldo = $totalMasuk - $totalKeluar;

    $kasTransaksis = $query
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    return view('admin.kas-transaksis.index', compact(
        'kasTransaksis',
        'totalMasuk',
        'totalKeluar',
        'saldo',
        'search',
        'tipe',
        'bulan',
        'tahun'
    ));
}

    public function create()
    {
        return view('admin.kas-transaksis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'in:masuk,keluar'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'judul' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        KasTransaksi::create($validated);

        return redirect()
            ->route('admin.kas-transaksis.index')
            ->with('success', 'Transaksi kas berhasil ditambahkan.');
    }

    public function edit(KasTransaksi $kasTransaksi)
    {
        return view('admin.kas-transaksis.edit', compact('kasTransaksi'));
    }

    public function update(Request $request, KasTransaksi $kasTransaksi)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'in:masuk,keluar'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'judul' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        $kasTransaksi->update($validated);

        return redirect()
            ->route('admin.kas-transaksis.index')
            ->with('success', 'Transaksi kas berhasil diperbarui.');
    }

    public function export(Request $request)
{
    $search = trim($request->get('search', ''));
    $tipe = $request->get('tipe');
    $bulan = $request->get('bulan');
    $tahun = $request->get('tahun');


    $fileName = 'laporan-kas-' . now()->format('Y-m-d-His') . '.csv';

    $kasTransaksis = KasTransaksi::query()
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        })
        ->when($tipe !== null && $tipe !== '', function ($query) use ($tipe) {
            $query->where('tipe', $tipe);
        })
        ->when($bulan !== null && $bulan !== '', function ($query) use ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        })
        ->when($tahun !== null && $tahun !== '', function ($query) use ($tahun) {
            $query->whereYear('tanggal', $tahun);
        })
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->get();

    $totalMasuk = $kasTransaksis->where('tipe', 'masuk')->sum('nominal');
    $totalKeluar = $kasTransaksis->where('tipe', 'keluar')->sum('nominal');
    $saldo = $totalMasuk - $totalKeluar;

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $callback = function () use ($kasTransaksis, $totalMasuk, $totalKeluar, $saldo) {
        $file = fopen('php://output', 'w');

        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        $delimiter = ';';

        fputcsv($file, ['Laporan Kas RT/RW'], $delimiter);
        fputcsv($file, ['Tanggal Export', now()->format('Y-m-d H:i:s')], $delimiter);
        fputcsv($file, [], $delimiter);

        fputcsv($file, ['Ringkasan'], $delimiter);
        fputcsv($file, ['Total Kas Masuk', 'Rp ' . number_format($totalMasuk, 0, ',', '.')], $delimiter);
        fputcsv($file, ['Total Kas Keluar', 'Rp ' . number_format($totalKeluar, 0, ',', '.')], $delimiter);
        fputcsv($file, ['Saldo Kas', 'Rp ' . number_format($saldo, 0, ',', '.')], $delimiter);
        fputcsv($file, [], $delimiter);

        fputcsv($file, [
            'No',
            'Tanggal',
            'Tipe',
            'Kategori',
            'Judul',
            'Keterangan',
            'Nominal',
        ], $delimiter);

        foreach ($kasTransaksis as $index => $item) {
            fputcsv($file, [
                $index + 1,
                $item->tanggal,
                ucfirst($item->tipe),
                $item->kategori ?? '-',
                $item->judul,
                $item->keterangan ?? '-',
                $item->nominal,
            ], $delimiter);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    public function exportPdf(Request $request)
{
    $search = trim($request->get('search', ''));
    $tipe = $request->get('tipe');
    $bulan = $request->get('bulan');
    $tahun = $request->get('tahun');

    $kasTransaksis = KasTransaksi::query()
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        })
        ->when($tipe !== null && $tipe !== '', function ($query) use ($tipe) {
            $query->where('tipe', $tipe);
        })
        ->when($bulan !== null && $bulan !== '', function ($query) use ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        })
        ->when($tahun !== null && $tahun !== '', function ($query) use ($tahun) {
            $query->whereYear('tanggal', $tahun);
        })
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->get();

    $totalMasuk = $kasTransaksis->where('tipe', 'masuk')->sum('nominal');
    $totalKeluar = $kasTransaksis->where('tipe', 'keluar')->sum('nominal');
    $saldo = $totalMasuk - $totalKeluar;

    $pdf = Pdf::loadView('admin.kas-transaksis.pdf', compact(
        'kasTransaksis',
        'totalMasuk',
        'totalKeluar',
        'saldo',
        'search',
        'tipe',
        'bulan',
        'tahun'
    ))->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-kas-' . now()->format('Y-m-d-His') . '.pdf');
}

    public function destroy(KasTransaksi $kasTransaksi)
    {
        $kasTransaksi->delete();

        return redirect()
            ->route('admin.kas-transaksis.index')
            ->with('success', 'Transaksi kas berhasil dihapus.');
    }
}
