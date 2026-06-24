<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use Illuminate\Http\Request;

class RtController extends Controller
{
    public function index()
    {
        $rts = Rt::latest()->paginate(10);

        return view('admin.rts.index', compact('rts'));
    }

    public function create()
    {
        return view('admin.rts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_rt' => ['required', 'string', 'max:10'],
            'nama_ketua_rt' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat_sekretariat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Rt::create($validated);

        return redirect()
            ->route('admin.rts.index')
            ->with('success', 'Data RT berhasil ditambahkan.');
    }

    public function edit(Rt $rt)
    {
        return view('admin.rts.edit', compact('rt'));
    }

    public function update(Request $request, Rt $rt)
    {
        $validated = $request->validate([
            'nomor_rt' => ['required', 'string', 'max:10'],
            'nama_ketua_rt' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat_sekretariat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $rt->update($validated);

        return redirect()
            ->route('admin.rts.index')
            ->with('success', 'Data RT berhasil diperbarui.');
    }

    public function destroy(Rt $rt)
    {
        $rt->delete();

        return redirect()
            ->route('admin.rts.index')
            ->with('success', 'Data RT berhasil dihapus.');
    }
}
