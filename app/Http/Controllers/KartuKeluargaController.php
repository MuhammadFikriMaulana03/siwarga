<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use App\Models\Rt;
use Illuminate\Http\Request;

class KartuKeluargaController extends Controller
{
    public function index(Request $request)
{
    $search = trim($request->get('search', ''));
    $statusAktif = $request->get('status_aktif');

    $kartuKeluargas = KartuKeluarga::with('rt')
        ->withCount('wargas')
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                    ->orWhere('kepala_keluarga', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhereHas('rt', function ($rtQuery) use ($search) {
                        $rtQuery->where('nomor_rt', 'like', "%{$search}%");
                    });
            });
        })
        ->when($statusAktif !== null && $statusAktif !== '', function ($query) use ($statusAktif) {
            $query->where('is_active', (bool) $statusAktif);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.kartu-keluargas.index', compact(
        'kartuKeluargas',
        'search',
        'statusAktif'
    ));
}

    public function create()
    {
        $rts = Rt::where('is_active', true)
            ->orderBy('nomor_rt')
            ->get();

        return view('admin.kartu-keluargas.create', compact('rts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rt_id' => ['required', 'exists:rts,id'],
            'no_kk' => ['required', 'string', 'max:20', 'unique:kartu_keluargas,no_kk'],
            'kepala_keluarga' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        KartuKeluarga::create($validated);

        return redirect()
            ->route('admin.kartu-keluargas.index')
            ->with('success', 'Kartu keluarga berhasil ditambahkan.');
    }

    public function show(KartuKeluarga $kartuKeluarga)
{
    $kartuKeluarga->load(['rt', 'wargas']);

    return view('admin.kartu-keluargas.show', compact('kartuKeluarga'));
}

    public function edit(KartuKeluarga $kartuKeluarga)
    {
        $rts = Rt::where('is_active', true)
            ->orderBy('nomor_rt')
            ->get();

        return view('admin.kartu-keluargas.edit', compact('kartuKeluarga', 'rts'));
    }

    public function update(Request $request, KartuKeluarga $kartuKeluarga)
    {
        $validated = $request->validate([
            'rt_id' => ['required', 'exists:rts,id'],
            'no_kk' => ['required', 'string', 'max:20', 'unique:kartu_keluargas,no_kk,' . $kartuKeluarga->id],
            'kepala_keluarga' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $kartuKeluarga->update($validated);

        return redirect()
            ->route('admin.kartu-keluargas.index')
            ->with('success', 'Kartu keluarga berhasil diperbarui.');
    }

    public function destroy(KartuKeluarga $kartuKeluarga)
    {
        $kartuKeluarga->delete();

        return redirect()
            ->route('admin.kartu-keluargas.index')
            ->with('success', 'Kartu keluarga berhasil dihapus.');
    }
}