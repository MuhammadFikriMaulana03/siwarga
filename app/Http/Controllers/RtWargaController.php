<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RtWargaController extends Controller
{
    public function index(Request $request)
{
    $user = User::findOrFail(Auth::id());

    $rt = $user->rt;

    $search = $request->get('search', '');
    $statusWarga = $request->get('status_warga', '');
    $statusAktif = $request->get('status_aktif', '');

    if (!$rt) {
        return view('rt.wargas.index', [
            'rt' => null,
            'wargas' => collect(),
            'search' => $search,
            'statusWarga' => $statusWarga,
            'statusAktif' => $statusAktif,
        ]);
    }

    $wargas = Warga::with(['rt', 'kartuKeluarga'])
        ->where('rt_id', $rt->id)

        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        })

        ->when($statusWarga !== '', function ($query) use ($statusWarga) {
            $query->where('status_warga', $statusWarga);
        })

        ->when($statusAktif !== '', function ($query) use ($statusAktif) {
            $query->where('is_active', $statusAktif);
        })

        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('rt.wargas.index', compact(
        'rt',
        'wargas',
        'search',
        'statusWarga',
        'statusAktif'
    ));
}

    public function show(Warga $warga)
    {
        $user = User::findOrFail(Auth::id());

        abort_if(
            (int) $warga->rt_id !== (int) $user->rt_id,
            403,
            'Anda tidak memiliki akses ke data warga ini.'
        );

        $warga->load(['rt', 'kartuKeluarga']);

        return view('rt.wargas.show', compact('warga'));
    }
}
