<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\ActivityLog;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function publicCreate()
{
    $rts = Rt::where('is_active', true)
        ->orderBy('nomor_rt')
        ->get();

    return view('pengaduan.create', compact('rts'));
}

    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'rt_id' => ['required', 'exists:rts,id'],
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengaduan', 'public');
        }

        $validated['status'] = 'masuk';

        $pengaduan = Pengaduan::create($validated);

        $pengaduan->update([
            'kode_tracking' => 'PGD-' . now()->format('Ymd') . '-' . str_pad($pengaduan->id, 4, '0', STR_PAD_LEFT),
        ]);

        return redirect()
            ->route('pengaduan.create')
            ->with('success', 'Pengaduan berhasil dikirim. Kode tracking Anda: ' . $pengaduan->kode_tracking);

        }

    public function index(Request $request)
{
    $search = $request->get('search');
    $status = $request->get('status');

    $pengaduans = Pengaduan::with('rt')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('judul', 'like', '%' . $search . '%')
                  ->orWhere('isi', 'like', '%' . $search . '%');
            });
        })
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.pengaduans.index', compact(
        'pengaduans',
        'search',
        'status'
    ));
}

    public function cekStatus()
{
    return view('pengaduan.cek');
}

public function cekStatusResult(Request $request)
{
    $validated = $request->validate([
        'kode_tracking' => ['required', 'string', 'max:50'],
    ]);

    $pengaduan = Pengaduan::where('kode_tracking', $validated['kode_tracking'])->first();

    if (!$pengaduan) {
        return back()
            ->withInput()
            ->with('error', 'Kode tracking tidak ditemukan.');
    }

    return view('pengaduan.cek', compact('pengaduan'));
}

    public function edit(Pengaduan $pengaduan)
    {
        return view('admin.pengaduans.edit', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:masuk,diproses,selesai,ditolak'],
            'tanggapan' => ['nullable', 'string'],
        ]);

        $pengaduan->update($validated);

        ActivityLog::record(
            'Pengaduan',
            'Update Pengaduan',
            'Mengubah status pengaduan: ' . ($pengaduan->kode_tracking ?? $pengaduan->judul)
        );

        return redirect()
            ->route('admin.pengaduans.index')
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        ActivityLog::record(
            'Pengaduan',
            'Hapus Pengaduan',
            'Menghapus pengaduan: ' . ($pengaduan->kode_tracking ?? $pengaduan->judul)
        );

        $pengaduan->delete();

        return redirect()
            ->route('admin.pengaduans.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}
