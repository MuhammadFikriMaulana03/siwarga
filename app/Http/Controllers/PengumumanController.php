<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::latest()->paginate(10);

        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,publish'],
            'tanggal_publish' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        Pengumuman::create($validated);

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,publish'],
            'tanggal_publish' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        $pengumuman->update($validated);

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->gambar) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}