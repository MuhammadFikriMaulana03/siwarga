<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaSuratController extends Controller
{
    public function index(Request $request)
    {
        $warga = Auth::user()->warga;

        abort_if(!$warga, 403);

        $search = trim($request->get('search', ''));
        $status = $request->get('status');

        $baseQuery = PengajuanSurat::with('jenisSurat')
            ->where(function ($query) use ($warga) {
                $query->where('warga_id', $warga->id)
                    ->orWhere('nik', $warga->nik);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_tracking', 'like', "%{$search}%")
                        ->orWhere('nama_pemohon', 'like', "%{$search}%")
                        ->orWhere('keperluan', 'like', "%{$search}%")
                        ->orWhereHas('jenisSurat', function ($jenisQuery) use ($search) {
                            $jenisQuery->where('nama', 'like', "%{$search}%");
                        });
                });
            });

        $totalPengajuan = (clone $baseQuery)->count();
        $menunggu = (clone $baseQuery)->where('status', 'menunggu')->count();
        $diproses = (clone $baseQuery)->where('status', 'diproses')->count();
        $selesai = (clone $baseQuery)->where('status', 'selesai')->count();

        $pengajuanSurats = (clone $baseQuery)
            ->when(
                !empty($status),
                fn ($query) => $query->where('status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('warga.surat.index', compact(
            'warga',
            'pengajuanSurats',
            'search',
            'status',
            'totalPengajuan',
            'menunggu',
            'diproses',
            'selesai'
        ));
    }

    public function create()
    {
        $warga = Auth::user()->warga;

        if (!$warga) {
            return redirect()
                ->route('warga.surat.index')
                ->with('error', 'Akun warga belum terhubung dengan data warga.');
        }

        $jenisSurats = JenisSurat::where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('warga.surat.create', compact(
            'warga',
            'jenisSurats'
        ));
    }

    public function store(Request $request)
    {
        $warga = Auth::user()->warga;

        abort_if(!$warga, 403);

        $validated = $request->validate([
            'jenis_surat_id' => [
                'required',
                'exists:jenis_surats,id'
            ],
            'no_hp' => [
                'nullable',
                'string',
                'max:20'
            ],
            'keperluan' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        $pengajuanSurat = PengajuanSurat::create([
            'jenis_surat_id' => $validated['jenis_surat_id'],

            // Data penting wajib dari server
            'warga_id' => $warga->id,
            'nama_pemohon' => $warga->nama,
            'nik' => $warga->nik,
            'alamat' => $warga->alamat,

            'no_hp' => $validated['no_hp'] ?: $warga->no_hp,
            'keperluan' => $validated['keperluan'],

            'status' => 'menunggu',
            'catatan_admin' => null,
        ]);

        $pengajuanSurat->update([
            'kode_tracking' => 'SRT-' .
                now()->format('Ymd') .
                '-' .
                str_pad(
                    $pengajuanSurat->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ),
        ]);

        return redirect()
            ->route('warga.surat.index')
            ->with(
                'success',
                'Pengajuan surat berhasil dikirim. Kode tracking: '
                . $pengajuanSurat->kode_tracking
            );
    }

    public function show(PengajuanSurat $pengajuanSurat)
    {
        $warga = Auth::user()->warga;

        abort_if(!$warga, 403);

        abort_if(
            $pengajuanSurat->warga_id !== $warga->id &&
            $pengajuanSurat->nik !== $warga->nik,
            403
        );

        $pengajuanSurat->load('jenisSurat');

        return view('warga.surat.show', compact(
            'pengajuanSurat',
            'warga'
        ));
    }
}
