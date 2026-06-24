<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RtPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->rt_id) {
            return view('rt.pengaduans.index', [
                'pengaduans' => collect(),
                'rt' => null,
                'search' => '',
                'status' => '',
                'totalPengaduan' => 0,
                'pengaduanMasuk' => 0,
                'pengaduanDiproses' => 0,
                'pengaduanSelesai' => 0,
            ]);
        }

        $search = trim($request->get('search', ''));
        $status = $request->get('status');

        $baseQuery = Pengaduan::with('rt')
            ->where('rt_id', $user->rt_id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_tracking', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%");
                });
            });

        $totalPengaduan = (clone $baseQuery)->count();
        $pengaduanMasuk = (clone $baseQuery)->where('status', 'masuk')->count();
        $pengaduanDiproses = (clone $baseQuery)->where('status', 'diproses')->count();
        $pengaduanSelesai = (clone $baseQuery)->where('status', 'selesai')->count();

        $pengaduans = $baseQuery
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $rt = $user->rt;

        return view('rt.pengaduans.index', compact(
            'pengaduans',
            'rt',
            'search',
            'status',
            'totalPengaduan',
            'pengaduanMasuk',
            'pengaduanDiproses',
            'pengaduanSelesai'
        ));
    }

    public function show(Pengaduan $pengaduan)
{
    $user = User::findOrFail(Auth::id());

    abort_if(
        (int) $pengaduan->rt_id !== (int) $user->rt_id,
        403,
        'Anda tidak memiliki akses ke pengaduan ini.'
    );

    return view('rt.pengaduans.show', compact('pengaduan'));
}

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $user = Auth::user();

        $user = User::findOrFail(Auth::id());

    abort_if(
    (int) $pengaduan->rt_id !== (int) $user->rt_id,
    403,
    'Anda tidak memiliki akses ke pengaduan ini.'
    );

        $validated = $request->validate([
            'status' => ['required', 'in:masuk,diproses,selesai,ditolak'],
            'tanggapan' => ['nullable', 'string'],
        ]);

        $pengaduan->update($validated);

        return redirect()
            ->route('rt.pengaduans.show', $pengaduan)
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}