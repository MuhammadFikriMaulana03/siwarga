<x-layouts.admin title="Tambah Kartu Keluarga" header="Tambah Kartu Keluarga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        <form action="{{ route('admin.kartu-keluargas.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">RT</label>
                <select name="rt_id"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih RT</option>
                    @foreach ($rts as $rt)
                        <option value="{{ $rt->id }}" @selected(old('rt_id') == $rt->id)>
                            RT {{ $rt->nomor_rt }} - {{ $rt->nama_ketua_rt ?? 'Ketua belum diisi' }}
                        </option>
                    @endforeach
                </select>
                @error('rt_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nomor Kartu Keluarga</label>
                <input type="text"
                       name="no_kk"
                       value="{{ old('no_kk') }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Contoh: 321xxxxxxxxxxxxx">
                @error('no_kk')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Kepala Keluarga</label>
                <input type="text"
                       name="kepala_keluarga"
                       value="{{ old('kepala_keluarga') }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Nama kepala keluarga">
                @error('kepala_keluarga')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Alamat</label>
                <textarea name="alamat"
                          rows="4"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Alamat lengkap keluarga">{{ old('alamat') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       id="is_active"
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                       @checked(old('is_active', true))>

                <label for="is_active" class="text-sm font-semibold">
                    Status Aktif
                </label>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Simpan
                </button>

                <a href="{{ route('admin.kartu-keluargas.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
