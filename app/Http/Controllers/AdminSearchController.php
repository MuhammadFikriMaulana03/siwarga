<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\PengajuanSurat;
use App\Models\Umkm;
use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $wargas = collect();
        $pengajuanSurats = collect();
        $pengaduans = collect();
        $umkms = collect();

        if ($q !== '') {
            $wargas = Warga::with(['rt', 'kartuKeluarga'])
                ->where(function ($query) use ($q) {
                    $query->where('nama', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('no_kk', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhere('alamat', 'like', "%{$q}%");
                })
                ->latest()
                ->take(10)
                ->get();

            $pengajuanSurats = PengajuanSurat::with('jenisSurat')
                ->where(function ($query) use ($q) {
                    $query->where('kode_tracking', 'like', "%{$q}%")
                        ->orWhere('nama_pemohon', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhere('keperluan', 'like', "%{$q}%")
                        ->orWhereHas('jenisSurat', function ($jenisQuery) use ($q) {
                            $jenisQuery->where('nama', 'like', "%{$q}%");
                        });
                })
                ->latest()
                ->take(10)
                ->get();

            $pengaduans = Pengaduan::with('rt')
                ->where(function ($query) use ($q) {
                    $query->where('kode_tracking', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhere('judul', 'like', "%{$q}%")
                        ->orWhere('isi', 'like', "%{$q}%");
                })
                ->latest()
                ->take(10)
                ->get();

            $umkms = Umkm::where(function ($query) use ($q) {
                    $query->where('nama_usaha', 'like', "%{$q}%")
                        ->orWhere('pemilik', 'like', "%{$q}%")
                        ->orWhere('kategori', 'like', "%{$q}%")
                        ->orWhere('alamat', 'like', "%{$q}%");
                })
                ->latest()
                ->take(10)
                ->get();

                ActivityLog::record(
                    'Pencarian',
                    'Pencarian Global',
                    'Admin mencari kata kunci: ' . $q
                );
        }

        return view('admin.search.index', compact(
            'q',
            'wargas',
            'pengajuanSurats',
            'pengaduans',
            'umkms'
        ));
    }
}
