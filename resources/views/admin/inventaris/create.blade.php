<x-layouts.admin
    title="Tambah Inventaris"
    header="Tambah Inventaris">

<div class="bg-white rounded-2xl shadow-sm p-6">

<form
    action="{{ route('admin.inventaris.store') }}"
    method="POST">

    @csrf

    <div class="grid gap-4">

        <div>
            <label>Kode Barang</label>

            <input
                type="text"
                name="kode_barang"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Nama Barang</label>

            <input
                type="text"
                name="nama_barang"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Kategori</label>

            <input
                type="text"
                name="kategori"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Jumlah</label>

            <input
                type="number"
                name="jumlah"
                value="1"
                class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label>Kondisi</label>

            <select
                name="kondisi"
                class="w-full rounded-xl border-slate-300">

                <option value="Baik">Baik</option>

                <option value="Rusak Ringan">
                    Rusak Ringan
                </option>

                <option value="Rusak Berat">
                    Rusak Berat
                </option>

            </select>
        </div>

        <div>
            <label>Keterangan</label>

            <textarea
                name="keterangan"
                rows="4"
                class="w-full rounded-xl border-slate-300">
            </textarea>
        </div>

        <button
            class="bg-indigo-600 text-white py-3 rounded-xl">

            Simpan

        </button>

    </div>

</form>

</div>

</x-layouts.admin>
