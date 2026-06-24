<x-layouts.admin title="Tambah Transaksi Kas" header="Tambah Transaksi Kas">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        <form action="{{ route('admin.kas-transaksis.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('tanggal')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Tipe Transaksi</label>
                <select name="tipe"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Tipe</option>
                    <option value="masuk" @selected(old('tipe') === 'masuk')>Kas Masuk</option>
                    <option value="keluar" @selected(old('tipe') === 'keluar')>Kas Keluar</option>
                </select>
                @error('tipe')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Kategori</label>
                <input type="text"
                       name="kategori"
                       value="{{ old('kategori') }}"
                       placeholder="Contoh: Iuran Bulanan, Donasi, Kebersihan, Keamanan"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Judul Transaksi</label>
                <input type="text"
                       name="judul"
                       value="{{ old('judul') }}"
                       placeholder="Contoh: Iuran warga bulan Juni"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('judul')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nominal</label>
                <input type="number"
                       name="nominal"
                       value="{{ old('nominal') }}"
                       min="0"
                       step="1"
                       placeholder="Contoh: 50000"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('nominal')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Keterangan</label>
                <textarea name="keterangan"
                          rows="4"
                          placeholder="Catatan tambahan transaksi"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Simpan
                </button>

                <a href="{{ route('admin.kas-transaksis.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
