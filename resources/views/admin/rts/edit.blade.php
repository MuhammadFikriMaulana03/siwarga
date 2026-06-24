<x-layouts.admin title="Edit RT" header="Edit RT">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
        <form action="{{ route('admin.rts.update', $rt) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Nomor RT</label>
                <input type="text" name="nomor_rt" value="{{ old('nomor_rt', $rt->nomor_rt) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('nomor_rt')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Ketua RT</label>
                <input type="text" name="nama_ketua_rt" value="{{ old('nama_ketua_rt', $rt->nama_ketua_rt) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $rt->no_hp) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Alamat Sekretariat</label>
                <textarea name="alamat_sekretariat" rows="4"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat_sekretariat', $rt->alamat_sekretariat) }}</textarea>
            </div>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1"
                       @checked(old('is_active', $rt->is_active))
                       class="rounded border-slate-300 text-indigo-600">
                <span class="text-sm font-semibold">RT Aktif</span>
            </label>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold">
                    Update
                </button>

                <a href="{{ route('admin.rts.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
