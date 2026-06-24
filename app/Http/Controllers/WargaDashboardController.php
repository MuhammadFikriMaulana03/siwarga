<?php

namespace App\Http\Controllers;

use App\Models\IuranWarga;
use App\Models\PengajuanSurat;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class WargaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $warga = $user->warga;

        if (!$warga) {
            return view('warga.dashboard', [
                'warga' => null,
                'totalIuran' => 0,
                'iuranLunas' => 0,
                'iuranBelumBayar' => 0,
                'jumlahBelumBayar' => 0,
                'iuranTerbaru' => collect(),
                'pengajuanSuratTerbaru' => collect(),
                'pengaduanTerbaru' => collect(),
                'totalPengajuanSurat' => 0,
                'pengajuanMenunggu' => 0,
                'pengajuanSelesai' => 0,
                'totalPengaduan' => 0,
                'pengaduanMasuk' => 0,
                'pengaduanSelesai' => 0,
            ]);
        }

        $iurans = IuranWarga::where('warga_id', $warga->id);

        $totalIuran = (clone $iurans)->sum('nominal');

        $iuranLunas = (clone $iurans)
            ->where('status', 'lunas')
            ->sum('nominal');

        $iuranBelumBayar = (clone $iurans)
            ->where('status', 'belum_bayar')
            ->sum('nominal');

        $jumlahBelumBayar = (clone $iurans)
            ->where('status', 'belum_bayar')
            ->count();

        $iuranTerbaru = IuranWarga::where('warga_id', $warga->id)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->take(5)
            ->get();

        $pengajuanSuratTerbaru = PengajuanSurat::with('jenisSurat')
            ->where('nik', $warga->nik)
            ->latest()
            ->take(5)
            ->get();

        $pengaduanTerbaru = Pengaduan::where('nama', $warga->nama)
            ->latest()
            ->take(5)
            ->get();

        $totalPengajuanSurat = PengajuanSurat::where('nik', $warga->nik)->count();

        $pengajuanMenunggu = PengajuanSurat::where('nik', $warga->nik)
            ->where('status', 'menunggu')
            ->count();

        $pengajuanSelesai = PengajuanSurat::where('nik', $warga->nik)
            ->where('status', 'selesai')
            ->count();

        $totalPengaduan = Pengaduan::where(function ($query) use ($warga) {
                $query->where('nama', $warga->nama)
                    ->orWhere('no_hp', $warga->no_hp);
            })
            ->count();

        $pengaduanMasuk = Pengaduan::where(function ($query) use ($warga) {
                $query->where('nama', $warga->nama)
                    ->orWhere('no_hp', $warga->no_hp);
            })
            ->where('status', 'masuk')
            ->count();

        $pengaduanSelesai = Pengaduan::where(function ($query) use ($warga) {
                $query->where('nama', $warga->nama)
                    ->orWhere('no_hp', $warga->no_hp);
            })
            ->where('status', 'selesai')
            ->count();

        return view('warga.dashboard', compact(
            'warga',
            'totalIuran',
            'iuranLunas',
            'iuranBelumBayar',
            'jumlahBelumBayar',
            'iuranTerbaru',
            'pengajuanSuratTerbaru',
            'pengaduanTerbaru',
            'totalPengajuanSurat',
            'pengajuanMenunggu',
            'pengajuanSelesai',
            'totalPengaduan',
            'pengaduanMasuk',
            'pengaduanSelesai',
        ));
    }
}