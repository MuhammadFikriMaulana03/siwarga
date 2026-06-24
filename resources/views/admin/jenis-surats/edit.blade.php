<x-layouts.admin>
    <div class="p-6 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.jenis-surats.index') }}"
               class="inline-flex mb-4 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                Kembali
            </a>

            <h1 class="text-2xl font-extrabold text-slate-900">Edit Jenis Surat</h1>
            <p class="text-slate-500 text-sm">Ubah layanan surat warga.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <form action="{{ route('admin.jenis-surats.update', $jenisSurat) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Jenis Surat</label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama', $jenisSurat->nama) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                           required>

                    @error('nama')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi"
                              rows="4"
                              class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $jenisSurat->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="is_active"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="1" {{ old('is_active', $jenisSurat->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $jenisSurat->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <button class="w-full px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>
