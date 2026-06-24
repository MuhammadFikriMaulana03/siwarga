<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $warga = Auth::user()->warga;

        abort_if(!$warga, 403);

        $search = trim($request->get('search', ''));
        $status = $request->get('status');

        $baseQuery = Pengaduan::with('rt')
            ->where(function ($query) use ($warga) {
                $query->where('nama', $warga->nama)
                    ->orWhere('no_hp', $warga->no_hp);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_tracking', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%");
                });
            });

        $totalPengaduan = (clone $baseQuery)->count();
        $masuk = (clone $baseQuery)->where('status', 'masuk')->count();
        $diproses = (clone $baseQuery)->where('status', 'diproses')->count();
        $selesai = (clone $baseQuery)->where('status', 'selesai')->count();

        $pengaduans = (clone $baseQuery)
            ->when(
                !empty($status),
                fn ($query) => $query->where('status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('warga.pengaduan.index', compact(
            'warga',
            'pengaduans',
            'search',
            'status',
            'totalPengaduan',
            'masuk',
            'diproses',
            'selesai'
        ));
    }

    public function create()
    {
        $warga = Auth::user()->warga;

        if (!$warga) {
            return redirect()
                ->route('warga.pengaduan.index')
                ->with('error', 'Akun warga belum terhubung dengan data warga.');
        }

        return view('warga.pengaduan.create', compact('warga'));
    }

    public function store(Request $request)
    {
        $warga = Auth::user()->warga;

        abort_if(!$warga, 403);

        $validated = $request->validate([
            'judul' => [
                'required',
                'string',
                'max:255'
            ],
            'isi' => [
                'required',
                'string',
                'max:3000'
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')
                ->store('pengaduan', 'public');
        }

        $pengaduan = Pengaduan::create([
            'rt_id' => $warga->rt_id,
            'nama' => $warga->nama,
            'no_hp' => $warga->no_hp,

            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'foto' => $fotoPath,

            'status' => 'masuk',
            'tanggapan' => null,
        ]);

        $pengaduan->update([
            'kode_tracking' => 'PGD-'
                . now()->format('Ymd')
                . '-'
                . str_pad(
                    $pengaduan->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ),
        ]);

        return redirect()
            ->route('warga.pengaduan.index')
            ->with(
                'success',
                'Pengaduan berhasil dikirim. Kode tracking: '
                . $pengaduan->kode_tracking
            );
    }

    public function show(Pengaduan $pengaduan)
    {
        $warga = Auth::user()->warga;

        abort_if(!$warga, 403);

        abort_if(
            $pengaduan->nama !== $warga->nama &&
            $pengaduan->no_hp !== $warga->no_hp,
            403
        );

        $pengaduan->load('rt');

        return view('warga.pengaduan.show', compact(
            'pengaduan',
            'warga'
        ));
    }
}
