<x-layouts.admin
    title="Edit Inventaris"
    header="Edit Inventaris">


<div class="bg-white rounded-2xl shadow-sm p-6">

<form action="{{ route('admin.inventaris.update', ['inventari' => $inventaris->id]) }}"
      method="POST">

    @csrf
    @method('PUT')

    <div class="grid gap-4">

        <div>
            <label>Kode Barang</label>
            <input
                type="text"
                name="kode_barang"
                value="{{ old('kode_barang', $inventaris->kode_barang) }}"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Nama Barang</label>
            <input
                type="text"
                name="nama_barang"
                value="{{ old('nama_barang', $inventaris->nama_barang) }}"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Kategori</label>
            <input
                type="text"
                name="kategori"
                value="{{ old('kategori', $inventaris->kategori) }}"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Jumlah</label>
            <input
                type="number"
                name="jumlah"
                value="{{ old('jumlah', $inventaris->jumlah) }}"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Kondisi</label>

            <select
                name="kondisi"
                class="w-full rounded-xl border-slate-300">

                <option value="Baik"
                    @selected($inventaris->kondisi == 'Baik')>
                    Baik
                </option>

                <option value="Rusak Ringan"
                    @selected($inventaris->kondisi == 'Rusak Ringan')>
                    Rusak Ringan
                </option>

                <option value="Rusak Berat"
                    @selected($inventaris->kondisi == 'Rusak Berat')>
                    Rusak Berat
                </option>

            </select>
        </div>

        <div>
            <label>Keterangan</label>

            <textarea
                name="keterangan"
                rows="4"
                class="w-full rounded-xl border-slate-300">{{ old('keterangan', $inventaris->keterangan) }}</textarea>
        </div>

        <button
            class="bg-indigo-600 text-white py-3 rounded-xl">

            Update Barang

        </button>

    </div>

</form>

</div>

</x-layouts.admin>
