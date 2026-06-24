<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use App\Models\Warga;
use App\Models\IuranWarga;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class RtDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rtId = $user->rt_id;

        if (!$rtId) {
            return view('rt.dashboard', [
                'rt' => null,
                'totalWarga' => 0,
                'totalKk' => 0,
                'totalIuranBulanIni' => 0,
                'totalIuranLunasBulanIni' => 0,
                'totalIuranBelumBayarBulanIni' => 0,
                'jumlahBelumBayar' => 0,
                'totalPengaduanRt' => 0,
                'pengaduanMasukRt' => 0,
                'pengaduanDiprosesRt' => 0,
                'pengaduanSelesaiRt' => 0,
                'wargaTerbaru' => collect(),
            ]);
        }

        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $rt = $user->rt;

        $totalWarga = Warga::where('rt_id', $rtId)->count();

        $totalKk = KartuKeluarga::where('rt_id', $rtId)->count();

        // Data Jenis Kelamin
        $lakiLaki = Warga::where('rt_id', $rtId)->where('jenis_kelamin', 'L')->count();
        $perempuan = Warga::where('rt_id', $rtId)->where('jenis_kelamin', 'P')->count();

        // Data Lansia (60 tahun ke atas)
        $batasLansia = now()->subYears(60)->format('Y-m-d');
        $lansia = Warga::where('rt_id', $rtId)
            ->where('tanggal_lahir', '<=', $batasLansia)
            ->count();
        $nonLansia = $totalWarga - $lansia;

        $totalIuranBulanIni = IuranWarga::whereHas('warga', function ($query) use ($rtId) {
                $query->where('rt_id', $rtId);
            })
            ->where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->sum('nominal');

        $totalIuranLunasBulanIni = IuranWarga::whereHas('warga', function ($query) use ($rtId) {
                $query->where('rt_id', $rtId);
            })
            ->where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'lunas')
            ->sum('nominal');

        $totalIuranBelumBayarBulanIni = IuranWarga::whereHas('warga', function ($query) use ($rtId) {
                $query->where('rt_id', $rtId);
            })
            ->where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'belum_bayar')
            ->sum('nominal');

        $jumlahBelumBayar = IuranWarga::whereHas('warga', function ($query) use ($rtId) {
                $query->where('rt_id', $rtId);
            })
            ->where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'belum_bayar')
            ->count();

        $wargaTerbaru = Warga::with('kartuKeluarga')
            ->where('rt_id', $rtId)
            ->latest()
            ->take(5)
            ->get();

        $totalPengaduanRt = Pengaduan::where('rt_id', $rtId)->count();

        $pengaduanMasukRt = Pengaduan::where('rt_id', $rtId)
            ->where('status', 'masuk')
            ->count();

        $pengaduanDiprosesRt = Pengaduan::where('rt_id', $rtId)
            ->where('status', 'diproses')
            ->count();

        $pengaduanSelesaiRt = Pengaduan::where('rt_id', $rtId)
            ->where('status', 'selesai')
            ->count();

        return view('rt.dashboard', compact(
            'rt',
            'totalWarga',
            'totalKk',
            'totalIuranBulanIni',
            'totalIuranLunasBulanIni',
            'totalIuranBelumBayarBulanIni',
            'jumlahBelumBayar',
            'wargaTerbaru',
            'totalPengaduanRt',
            'pengaduanMasukRt',
            'pengaduanDiprosesRt',
            'pengaduanSelesaiRt',
            'lakiLaki',
            'perempuan',
            'lansia',
            'nonLansia',
        ));
    }
}