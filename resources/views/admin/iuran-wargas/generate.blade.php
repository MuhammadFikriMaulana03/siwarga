<x-layouts.admin title="Generate Iuran Massal" header="Generate Iuran Massal">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        <div class="mb-6">
            <h3 class="text-lg font-bold">Generate Iuran untuk Semua Warga Aktif</h3>
            <p class="text-sm text-slate-500 mt-1">
                Sistem akan membuat tagihan iuran bulanan untuk semua warga aktif.
                Jika warga sudah punya tagihan pada bulan dan tahun yang sama, data akan dilewati.
            </p>
        </div>

        <form action="{{ route('admin.iuran-wargas.generate') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold mb-2">Bulan</label>
                    <select name="bulan"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Bulan</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" @selected(old('bulan', now()->month) == $month)>
                                {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    @error('bulan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Tahun</label>
                    <input type="number"
                           name="tahun"
                           value="{{ old('tahun', now()->year) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('tahun')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nominal Iuran per Warga</label>
                <input type="number"
                       name="nominal"
                       value="{{ old('nominal', 10000) }}"
                       min="0"
                       step="1"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('nominal')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Keterangan</label>
                <textarea name="keterangan"
                          rows="4"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Contoh: Iuran keamanan dan kebersihan bulan ini">{{ old('keterangan') }}</textarea>
            </div>

            <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100 text-amber-800">
                <p class="font-bold mb-1">Catatan</p>
                <p class="text-sm">
                    Generate ini hanya membuat tagihan dengan status <strong>Belum Bayar</strong>.
                    Jika warga sudah membayar, ubah statusnya menjadi <strong>Lunas</strong>, lalu sistem otomatis menambahkan transaksi ke Kas Masuk.
                </p>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700"
                        onclick="return confirm('Yakin generate iuran untuk semua warga aktif?')">
                    Generate Iuran
                </button>

                <a href="{{ route('admin.iuran-wargas.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
