<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\Kegiatan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use App\Models\KasTransaksi;
use App\Models\IuranWarga;
use App\Models\Rt;
use App\Models\Warga;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::allSettings();
        $totalWarga = Warga::count();
        $totalRt = Rt::count();
        $daftarRtLanding = Rt::whereIn('nomor_rt', ['01', '02', '1', '2'])
            ->orderByRaw('CAST(nomor_rt AS UNSIGNED)')
            ->get();

        $totalKk = Warga::whereNotNull('no_kk')
            ->where('no_kk', '!=', '')
            ->distinct('no_kk')
            ->count('no_kk');

        $pengumumanTerbaru = Pengumuman::where('status', 'publish')
            ->latest()
            ->take(3)
            ->get();

        $kegiatanTerbaru = Kegiatan::where('status', 'publish')
            ->latest()
            ->take(3)
            ->get();

        $umkmTerbaru = Umkm::where('status', 'publish')
            ->latest()
            ->take(3)
            ->get();

        $totalKasMasuk = KasTransaksi::where('tipe', 'masuk')->sum('nominal');
        $totalKasKeluar = KasTransaksi::where('tipe', 'keluar')->sum('nominal');
        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        $kasTerbaru = KasTransaksi::latest('tanggal')
            ->take(5)
            ->get();

        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $totalIuranBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->sum('nominal');

        $totalIuranLunasBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'lunas')
            ->sum('nominal');

        $totalIuranBelumBayarBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'belum_bayar')
            ->sum('nominal');

        $jumlahIuranBelumBayarBulanIni = IuranWarga::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'belum_bayar')
            ->count();

        // Data Analytics - Jenis Kelamin (L = Laki-laki, P = Perempuan)
        $totalLakiLaki = Warga::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Warga::where('jenis_kelamin', 'P')->count();

        // Data Analytics - Lansia (60 tahun ke atas)
        $tanggalLansia = now()->subYears(60)->format('Y-m-d');
        $totalLansia = Warga::whereDate('tanggal_lahir', '<=', $tanggalLansia)->count();
        $totalNonLansia = $totalWarga - $totalLansia;

        return view('landing', compact(
            'settings',
            'totalWarga',
            'totalRt',
            'daftarRtLanding',
            'totalKk',
            'pengumumanTerbaru',
            'kegiatanTerbaru',
            'umkmTerbaru',
            'totalKasMasuk',
            'totalKasKeluar',
            'saldoKas',
            'kasTerbaru',
            'bulanIni',
            'tahunIni',
            'totalIuranBulanIni',
            'totalIuranLunasBulanIni',
            'totalIuranBelumBayarBulanIni',
            'jumlahIuranBelumBayarBulanIni',
            'totalLakiLaki',
            'totalPerempuan',
            'totalLansia',
            'totalNonLansia',
        ));
    }

    public function cekIuran()
{
    return view('cek-iuran');
}

public function cekIuranResult(Request $request)
{
    $validated = $request->validate([
        'nik' => ['required', 'string', 'max:20'],
    ]);

    $warga = Warga::with(['rt', 'kartuKeluarga'])
        ->where('nik', $validated['nik'])
        ->first();

    if (!$warga) {
        return back()
            ->withInput()
            ->with('error', 'Data warga dengan NIK tersebut tidak ditemukan.');
    }

    $iurans = IuranWarga::where('warga_id', $warga->id)
        ->orderByDesc('tahun')
        ->orderByDesc('bulan')
        ->get();

    $totalTagihan = $iurans->sum('nominal');
    $totalLunas = $iurans->where('status', 'lunas')->sum('nominal');
    $totalBelumBayar = $iurans->where('status', 'belum_bayar')->sum('nominal');

    return view('cek-iuran', compact(
        'warga',
        'iurans',
        'totalTagihan',
        'totalLunas',
        'totalBelumBayar'
    ));
}

    public function showPengumuman(Pengumuman $pengumuman)
    {
        abort_if($pengumuman->status !== 'publish', 404);

        $pengumumanLainnya = Pengumuman::where('status', 'publish')
            ->where('id', '!=', $pengumuman->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pengumuman.show', compact('pengumuman', 'pengumumanLainnya'));
    }

    public function showKegiatan(Kegiatan $kegiatan)
{
    abort_if($kegiatan->status !== 'publish', 404);

    $kegiatanLainnya = Kegiatan::where('status', 'publish')
        ->where('id', '!=', $kegiatan->id)
        ->latest()
        ->take(3)
        ->get();

    return view('kegiatan.show', compact('kegiatan', 'kegiatanLainnya'));
}

public function showUmkm(Umkm $umkm)
{
    abort_if($umkm->status !== 'publish', 404);

    $umkmLainnya = Umkm::where('status', 'publish')
        ->where('id', '!=', $umkm->id)
        ->latest()
        ->take(3)
        ->get();

    return view('umkm.show', compact('umkm', 'umkmLainnya'));
}


}
