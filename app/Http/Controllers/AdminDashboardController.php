<?php

namespace App\Http\Controllers;

use App\Models\IuranWarga;
use App\Models\KartuKeluarga;
use App\Models\KasTransaksi;
use App\Models\Kegiatan;
use App\Models\Pengaduan;
use App\Models\PengajuanSurat;
use App\Models\Pengumuman;
use App\Models\Rt;
use App\Models\SystemSetting;
use App\Models\Umkm;
use App\Models\Warga;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {

        $bulanIni = (int) $request->get('bulan', now()->month);
        $tahunIni = (int) $request->get('tahun', now()->year);
        $totalWarga = Warga::count();
        $totalRt = Rt::count();
        $totalKk = KartuKeluarga::count();

        $pengajuanSurat = PengajuanSurat::count();
        $pengajuanMenunggu = PengajuanSurat::where('status', 'menunggu')->count();

        $pengaduanMasuk = Pengaduan::where('status', 'masuk')->count();
        $pengaduanDiproses = Pengaduan::where('status', 'diproses')->count();
        $pengaduanSelesai = Pengaduan::where('status', 'selesai')->count();
        $totalPengaduan = Pengaduan::count();

        $totalUmkm = Umkm::count();
        $totalPengumuman = Pengumuman::count();
        $totalKegiatan = Kegiatan::count();

        $wargaTerbaru = Warga::with(['rt', 'kartuKeluarga'])
            ->latest()
            ->take(5)
            ->get();

        $pengajuanTerbaru = PengajuanSurat::with('jenisSurat')
            ->latest()
            ->take(5)
            ->get();

        $pengaduanTerbaru = Pengaduan::latest()
            ->take(5)
            ->get();

        // Kas
        $totalKasMasuk = KasTransaksi::where('tipe', 'masuk')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal');

        $totalKasKeluar = KasTransaksi::where('tipe', 'keluar')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal');

        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        $kasTerbaru = KasTransaksi::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->take(5)
            ->get();



        $totalIuranBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->sum('nominal');

        $iuranLunasBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'lunas')
            ->sum('nominal');

        $iuranBelumBayarBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'belum_bayar')
            ->sum('nominal');

        $jumlahIuranBelumBayarBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'belum_bayar')
            ->count();

        // Persentase kas
        $maxKas = max($totalKasMasuk, $totalKasKeluar, $saldoKas, 1);

        $persenKasMasuk = round(($totalKasMasuk / $maxKas) * 100);
        $persenKasKeluar = round(($totalKasKeluar / $maxKas) * 100);
        $persenSaldoKas = round(($saldoKas / $maxKas) * 100);

        // Persentase iuran
        $persenIuranLunas = $totalIuranBulanIni > 0
            ? round(($iuranLunasBulanIni / $totalIuranBulanIni) * 100)
            : 0;

        $persenIuranBelumBayar = $totalIuranBulanIni > 0
            ? round(($iuranBelumBayarBulanIni / $totalIuranBulanIni) * 100)
            : 0;

        // Persentase pengaduan
        $totalStatusPengaduan = max(
            $pengaduanMasuk + $pengaduanDiproses + $pengaduanSelesai,
            1
        );

        $persenPengaduanMasuk = round(($pengaduanMasuk / $totalStatusPengaduan) * 100);
        $persenPengaduanDiproses = round(($pengaduanDiproses / $totalStatusPengaduan) * 100);
        $persenPengaduanSelesai = round(($pengaduanSelesai / $totalStatusPengaduan) * 100);

        // Data Analytics - Jenis Kelamin (L = Laki-laki, P = Perempuan)
        $totalLakiLaki = Warga::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Warga::where('jenis_kelamin', 'P')->count();
        $maxJk = max($totalLakiLaki + $totalPerempuan, 1);
        $persenLakiLaki = round(($totalLakiLaki / $maxJk) * 100);
        $persenPerempuan = round(($totalPerempuan / $maxJk) * 100);

        // Data Analytics - Lansia (60 tahun ke atas)
        $tanggalLansia = now()->subYears(60)->format('Y-m-d');
        $totalLansia = Warga::whereDate('tanggal_lahir', '<=', $tanggalLansia)->count();
        $totalNonLansia = $totalWarga - $totalLansia;
        $maxUsia = max($totalLansia, $totalNonLansia, 1);
        $persenLansia = round(($totalLansia / $maxUsia) * 100);
        $persenNonLansia = round(($totalNonLansia / $maxUsia) * 100);

        // Settings
        $settings = SystemSetting::allSettings();

        return view('admin.dashboard', compact(
            'totalWarga',
            'totalRt',
            'totalKk',
            'pengajuanSurat',
            'pengajuanMenunggu',
            'pengaduanMasuk',
            'pengaduanDiproses',
            'pengaduanSelesai',
            'totalPengaduan',
            'totalUmkm',
            'totalPengumuman',
            'totalKegiatan',
            'wargaTerbaru',
            'pengajuanTerbaru',
            'pengaduanTerbaru',
            'totalKasMasuk',
            'totalKasKeluar',
            'saldoKas',
            'kasTerbaru',
            'bulanIni',
            'tahunIni',
            'totalIuranBulanIni',
            'iuranLunasBulanIni',
            'iuranBelumBayarBulanIni',
            'jumlahIuranBelumBayarBulanIni',
            'persenKasMasuk',
            'persenKasKeluar',
            'persenSaldoKas',
            'persenIuranLunas',
            'persenIuranBelumBayar',
            'persenPengaduanMasuk',
            'persenPengaduanDiproses',
            'persenPengaduanSelesai',
            'totalLakiLaki',
            'totalPerempuan',
            'persenLakiLaki',
            'persenPerempuan',
            'totalLansia',
            'totalNonLansia',
            'persenLansia',
            'persenNonLansia',
            'settings'
        ));
    }
}
