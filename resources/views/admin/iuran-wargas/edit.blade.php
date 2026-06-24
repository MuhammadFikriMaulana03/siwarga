<x-layouts.admin title="Edit Iuran Warga" header="Edit Iuran Warga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.iuran-wargas.update', $iuranWarga) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Warga</label>
                <select name="warga_id"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Warga</option>
                    @foreach ($wargas as $warga)
                        <option value="{{ $warga->id }}" @selected(old('warga_id', $iuranWarga->warga_id) == $warga->id)>
                            {{ $warga->nama }} - NIK {{ $warga->nik }} - RT {{ $warga->rt->nomor_rt ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('warga_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold mb-2">Bulan</label>
                    <select name="bulan"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" @selected(old('bulan', $iuranWarga->bulan) == $month)>
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
                           value="{{ old('tahun', $iuranWarga->tahun) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('tahun')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nominal</label>
                <input type="number"
                       name="nominal"
                       value="{{ old('nominal', $iuranWarga->nominal) }}"
                       min="0"
                       step="1"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('nominal')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="belum_bayar" @selected(old('status', $iuranWarga->status) === 'belum_bayar')>Belum Bayar</option>
                    <option value="lunas" @selected(old('status', $iuranWarga->status) === 'lunas')>Lunas</option>
                </select>
                <p class="text-xs text-slate-500 mt-1">
                    Jika status diubah dari Belum Bayar ke Lunas, transaksi kas masuk akan otomatis dibuat.
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Tanggal Bayar</label>
                <input type="date"
                       name="tanggal_bayar"
                       value="{{ old('tanggal_bayar', $iuranWarga->tanggal_bayar) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Keterangan</label>
                <textarea name="keterangan"
                          rows="4"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('keterangan', $iuranWarga->keterangan) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Update
                </button>

                <a href="{{ route('admin.iuran-wargas.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
