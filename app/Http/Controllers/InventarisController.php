<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index()
    {
        $inventaris = Inventaris::latest()->get();

        return view(
            'admin.inventaris.index',
            compact('inventaris')
        );
    }

    public function create()
    {
        return view('admin.inventaris.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'jumlah' => 'required|integer',
            'kondisi' => 'required',
        ]);

        Inventaris::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('admin.inventaris.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
{
    $inventaris = Inventaris::findOrFail($id);

    return view(
        'admin.inventaris.edit',
        compact('inventaris')
    );
}

public function update(
    Request $request,
    Inventaris $inventaris
)
{
    $request->validate([
        'kode_barang' => 'required',
        'nama_barang' => 'required',
        'kategori' => 'required',
        'jumlah' => 'required|integer',
        'kondisi' => 'required',
    ]);

    $inventaris->update([
        'kode_barang' => $request->kode_barang,
        'nama_barang' => $request->nama_barang,
        'kategori' => $request->kategori,
        'jumlah' => $request->jumlah,
        'kondisi' => $request->kondisi,
        'keterangan' => $request->keterangan,
    ]);

    return redirect()
        ->route('admin.inventaris.index')
        ->with('success', 'Barang berhasil diupdate');
}
}