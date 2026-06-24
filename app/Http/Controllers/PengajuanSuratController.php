<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\SystemSetting;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\Rt;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Warga;
use Illuminate\Http\Request;

class PengajuanSuratController extends Controller
{


    public function publicCreate()
{
    $jenisSurats = JenisSurat::where('is_active', true)
        ->orderBy('nama')
        ->get();

    return view('layanan-surat.create', compact('jenisSurats'));
}

public function publicStore(Request $request)
{
    $validated = $request->validate([
        'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
        'nama_pemohon' => ['required', 'string', 'max:255'],
        'nik' => ['required', 'string', 'max:20'],
        'no_hp' => ['nullable', 'string', 'max:20'],
        'alamat' => ['nullable', 'string'],
        'keperluan' => ['required', 'string'],
    ]);

    $validated['status'] = 'menunggu';
    $validated['warga_id'] = null;
    $validated['catatan_admin'] = null;

    PengajuanSurat::create($validated);

    return redirect()
        ->route('layanan-surat.create')
        ->with('success', 'Pengajuan surat berhasil dikirim. Silakan tunggu proses dari admin RT/RW.');
}


    public function index(Request $request)
{
    $search = $request->get('search');
    $status = $request->get('status');

    $pengajuanSurats = PengajuanSurat::with(['jenisSurat', 'warga'])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pemohon', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhereHas('jenisSurat', function ($jenisQuery) use ($search) {
                      $jenisQuery->where('nama', 'like', '%' . $search . '%');
                  });
            });
        })
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.pengajuan-surats.index', compact(
        'pengajuanSurats',
        'search',
        'status'
    ));
}

    public function cetak(PengajuanSurat $pengajuanSurat)
{
    $pengajuanSurat->load(['jenisSurat', 'warga']);

    if ($pengajuanSurat->status !== 'selesai') {
        return back()->with('error', 'Surat hanya bisa dicetak jika status sudah selesai.');
    }

    $settings = SystemSetting::allSettings();

    $rt01 = Rt::whereIn('nomor_rt', ['1', '01', 'RT 01'])->first();
    $rt02 = Rt::whereIn('nomor_rt', ['2', '02', 'RT 02'])->first();

    $namaKetuaRt01 = $rt01?->nama_ketua_rt ?: '................................';
    $namaKetuaRt02 = $rt02?->nama_ketua_rt ?: '................................';

    $dataWarga = $pengajuanSurat->warga
        ?: Warga::where('nik', $pengajuanSurat->nik)->first();

    $pdf = Pdf::loadView('admin.pengajuan-surats.cetak', compact(
        'pengajuanSurat',
        'settings',
        'rt01',
        'rt02',
        'namaKetuaRt01',
        'namaKetuaRt02',
        'dataWarga'
    ))->setPaper('a4', 'portrait');

    ActivityLog::record(
        'Pengajuan Surat',
        'Cetak Surat',
        'Mencetak surat: ' . ($pengajuanSurat->kode_tracking ?? $pengajuanSurat->nama_pemohon)
    );

    return $pdf->stream('surat-' . $pengajuanSurat->id . '.pdf');
}

    public function create()
    {
        $jenisSurats = JenisSurat::where('is_active', true)->orderBy('nama')->get();
        $wargas = Warga::orderBy('nama')->get();

        return view('admin.pengajuan-surats.create', compact('jenisSurats', 'wargas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
            'warga_id' => ['nullable', 'exists:wargas,id'],
            'nama_pemohon' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'keperluan' => ['required', 'string'],
            'status' => ['required', 'in:menunggu,diproses,selesai,ditolak'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $pengajuanSurat = PengajuanSurat::create($validated);

        ActivityLog::record(
            'Pengajuan Surat',
            'Tambah Pengajuan',
            'Menambahkan pengajuan surat untuk: ' . $pengajuanSurat->nama_pemohon
        );

        return redirect()
            ->route('admin.pengajuan-surats.index')
            ->with('success', 'Pengajuan surat berhasil ditambahkan.');
    }

    public function edit(PengajuanSurat $pengajuanSurat)
{
    $jenisSurats = JenisSurat::where('is_active', true)->orderBy('nama')->get();
    $wargas = Warga::orderBy('nama')->get();

    return view('admin.pengajuan-surats.edit', compact(
        'pengajuanSurat',
        'jenisSurats',
        'wargas'
    ));
}

    public function update(Request $request, PengajuanSurat $pengajuanSurat)
    {
        $validated = $request->validate([
            'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
            'warga_id' => ['nullable', 'exists:wargas,id'],
            'nama_pemohon' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'keperluan' => ['required', 'string'],
            'status' => ['required', 'in:menunggu,diproses,selesai,ditolak'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $pengajuanSurat->update($validated);

        ActivityLog::record(
            'Pengajuan Surat',
            'Update Pengajuan',
            'Mengubah pengajuan surat: ' . ($pengajuanSurat->kode_tracking ?? $pengajuanSurat->nama_pemohon)
        );

        return redirect()
            ->route('admin.pengajuan-surats.index')
            ->with('success', 'Pengajuan surat berhasil diperbarui.');
    }

    public function destroy(PengajuanSurat $pengajuanSurat)
    {
        $pengajuanSurat->delete();

        return redirect()
            ->route('admin.pengajuan-surats.index')
            ->with('success', 'Pengajuan surat berhasil dihapus.');
    }
}