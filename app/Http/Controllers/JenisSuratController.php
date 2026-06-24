<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $status = $request->get('status');

        $jenisSurats = JenisSurat::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.jenis-surats.index', compact('jenisSurats', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.jenis-surats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $jenisSurat = JenisSurat::create($validated);

        ActivityLog::record(
            'Jenis Surat',
            'Tambah Jenis Surat',
            'Menambahkan jenis surat: ' . $jenisSurat->nama
        );

        return redirect()
            ->route('admin.jenis-surats.index')
            ->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function edit(JenisSurat $jenisSurat)
    {
        return view('admin.jenis-surats.edit', compact('jenisSurat'));
    }

    public function update(Request $request, JenisSurat $jenisSurat)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $jenisSurat->update($validated);

        ActivityLog::record(
            'Jenis Surat',
            'Edit Jenis Surat',
            'Mengubah jenis surat: ' . $jenisSurat->nama
        );

        return redirect()
            ->route('admin.jenis-surats.index')
            ->with('success', 'Jenis surat berhasil diperbarui.');
    }

    public function destroy(JenisSurat $jenisSurat)
    {
        if ($jenisSurat->pengajuanSurats()->exists()) {
            return back()->with('error', 'Jenis surat tidak bisa dihapus karena sudah dipakai di pengajuan surat.');
        }

        ActivityLog::record(
            'Jenis Surat',
            'Hapus Jenis Surat',
            'Menghapus jenis surat: ' . $jenisSurat->nama
        );

        $jenisSurat->delete();

        return redirect()
            ->route('admin.jenis-surats.index')
            ->with('success', 'Jenis surat berhasil dihapus.');
    }
}