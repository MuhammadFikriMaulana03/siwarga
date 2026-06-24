<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function index()
    {
        $umkms = Umkm::with('warga')->latest()->paginate(10);

        return view('admin.umkms.index', compact('umkms'));
    }

    public function create()
    {
        $wargas = Warga::orderBy('nama')->get();

        return view('admin.umkms.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => ['nullable', 'exists:wargas,id'],
            'nama_usaha' => ['required', 'string', 'max:255'],
            'pemilik' => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', 'in:draft,publish'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('umkm', 'public');
        }

        Umkm::create($validated);

        return redirect()
            ->route('admin.umkms.index')
            ->with('success', 'UMKM berhasil ditambahkan.');
    }

    public function edit(Umkm $umkm)
    {
        $wargas = Warga::orderBy('nama')->get();

        return view('admin.umkms.edit', compact('umkm', 'wargas'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'warga_id' => ['nullable', 'exists:wargas,id'],
            'nama_usaha' => ['required', 'string', 'max:255'],
            'pemilik' => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'in:draft,publish'],
        ]);

        if ($request->hasFile('foto')) {
            if ($umkm->foto) {
                Storage::disk('public')->delete($umkm->foto);
            }

            $validated['foto'] = $request->file('foto')->store('umkm', 'public');
        }

        $umkm->update($validated);

        return redirect()
            ->route('admin.umkms.index')
            ->with('success', 'UMKM berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm)
    {
        if ($umkm->foto) {
            Storage::disk('public')->delete($umkm->foto);
        }

        $umkm->delete();

        return redirect()
            ->route('admin.umkms.index')
            ->with('success', 'UMKM berhasil dihapus.');
    }
}
