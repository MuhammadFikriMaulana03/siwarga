<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\IuranWarga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RtIuranWargaController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $rt = $user->rt;

        if (!$user->rt_id) {
            return view('rt.iuran-wargas.index', [
                'rt' => null,
                'iuranWargas' => collect(),
                'search' => $request->get('search'),
                'status' => $request->get('status'),
                'bulan' => $request->get('bulan', now()->month),
                'tahun' => $request->get('tahun', now()->year),
                'totalTagihan' => 0,
                'totalLunas' => 0,
                'totalBelumBayar' => 0,
            ]);
        }

        $search = $request->get('search');
        $status = $request->get('status');
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $iuranWargas = IuranWarga::with('warga')
            ->whereHas('warga', function ($query) use ($user) {
                $query->where('rt_id', $user->rt_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('warga', function ($wargaQuery) use ($search) {
                    $wargaQuery->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%')
                        ->orWhere('no_kk', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($bulan, function ($query) use ($bulan) {
                $query->where('bulan', $bulan);
            })
            ->when($tahun, function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalTagihan = $iuranWargas->sum('nominal');

        $totalLunas = $iuranWargas
            ->where('status', 'lunas')
            ->sum('nominal');

        $totalBelumBayar = $iuranWargas
            ->where('status', 'belum_bayar')
            ->sum('nominal');

        return view('rt.iuran-wargas.index', compact(
    'rt',
    'iuranWargas',
    'search',
    'status',
    'bulan',
    'tahun',
    'totalTagihan',
    'totalLunas',
    'totalBelumBayar'
));
    }

    public function tandaiLunas(IuranWarga $iuranWarga)
    {
        $user = User::findOrFail(Auth::id());

        $iuranWarga->load('warga');

        abort_if(
            !$iuranWarga->warga || $iuranWarga->warga->rt_id !== $user->rt_id,
            403
        );

        $iuranWarga->update([
            'status' => 'lunas',
            'tanggal_bayar' => now(),
        ]);

        ActivityLog::record(
            'Iuran Warga',
            'Tandai Lunas oleh RT',
            'Ketua RT menandai lunas iuran warga: ' . ($iuranWarga->warga->nama ?? '-')
        );

        return back()->with('success', 'Iuran berhasil ditandai lunas.');
    }
}