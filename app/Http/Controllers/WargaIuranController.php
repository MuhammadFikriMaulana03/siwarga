<?php

namespace App\Http\Controllers;

use App\Models\IuranWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaIuranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $warga = $user->warga;

        abort_if(!$warga, 403);

        $iurans = \App\Models\IuranWarga::where('warga_id', $warga->id)
        ->latest()
        ->paginate(10);

        if (!$warga) {
            return view('warga.iuran.index', [
                'warga' => null,
                'iurans' => collect(),
                'status' => '',
                'bulan' => '',
                'tahun' => '',
                'totalTagihan' => 0,
                'totalLunas' => 0,
                'totalBelumBayar' => 0,
            ]);
        }

        $status = $request->get('status');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $baseQuery = IuranWarga::where('warga_id', $warga->id)
            ->when($bulan !== null && $bulan !== '', function ($query) use ($bulan) {
                $query->where('bulan', $bulan);
            })
            ->when($tahun !== null && $tahun !== '', function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            });

        $totalTagihan = (clone $baseQuery)->sum('nominal');
        $totalLunas = (clone $baseQuery)->where('status', 'lunas')->sum('nominal');
        $totalBelumBayar = (clone $baseQuery)->where('status', 'belum_bayar')->sum('nominal');

        $iurans = $baseQuery
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(10)
            ->withQueryString();

        return view('warga.iuran.index', compact(
            'warga',
            'iurans',
            'status',
            'bulan',
            'tahun',
            'totalTagihan',
            'totalLunas',
            'totalBelumBayar'
        ));
    }
}