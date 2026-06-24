<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::latest()->paginate(10);

        return view('admin.kegiatans.index', compact('kegiatans'));
    }

    public function create()
    {
        return view('admin.kegiatans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['nullable'],
            'jam_selesai' => ['nullable'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,publish'],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('kegiatan', 'public');
        }

        Kegiatan::create($validated);

        return redirect()
            ->route('admin.kegiatans.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatans.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['nullable'],
            'jam_selesai' => ['nullable'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,publish'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($kegiatan->gambar) {
                Storage::disk('public')->delete($kegiatan->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('kegiatan', 'public');
        }

        $kegiatan->update($validated);

        return redirect()
            ->route('admin.kegiatans.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        if ($kegiatan->gambar) {
            Storage::disk('public')->delete($kegiatan->gambar);
        }

        $kegiatan->delete();

        return redirect()
            ->route('admin.kegiatans.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}