<x-layouts.admin title="Tambah RT" header="Tambah RT">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
        <form action="{{ route('admin.rts.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Nomor RT</label>
                <input type="text" name="nomor_rt" value="{{ old('nomor_rt') }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Contoh: 01">
                @error('nomor_rt')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Ketua RT</label>
                <input type="text" name="nama_ketua_rt" value="{{ old('nama_ketua_rt') }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Contoh: Bapak Ahmad">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Contoh: 08123456789">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Alamat Sekretariat</label>
                <textarea name="alamat_sekretariat" rows="4"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Alamat sekretariat RT">{{ old('alamat_sekretariat') }}</textarea>
            </div>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" checked
                       class="rounded border-slate-300 text-indigo-600">
                <span class="text-sm font-semibold">RT Aktif</span>
            </label>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold">
                    Simpan
                </button>

                <a href="{{ route('admin.rts.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
