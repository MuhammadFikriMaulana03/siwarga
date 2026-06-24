<x-layouts.admin title="Edit UMKM" header="Edit UMKM">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        <form action="{{ route('admin.umkms.update', $umkm) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Pemilik dari Data Warga</label>
                <select name="warga_id" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tidak pilih / isi manual</option>
                    @foreach ($wargas as $warga)
                        <option value="{{ $warga->id }}" @selected(old('warga_id', $umkm->warga_id) == $warga->id)>
                            {{ $warga->nama }} - RT {{ $warga->rt->nomor_rt ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Usaha</label>
                <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $umkm->nama_usaha) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('nama_usaha')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Pemilik Manual</label>
                <input type="text" name="pemilik" value="{{ old('pemilik', $umkm->pemilik) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori', $umkm->kategori) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="5"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">No HP / WhatsApp</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $umkm->no_hp) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Alamat Usaha</label>
                <textarea name="alamat" rows="3"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat', $umkm->alamat) }}</textarea>
            </div>

            @if ($umkm->foto)
                <div>
                    <p class="text-sm font-semibold mb-2">Foto Saat Ini</p>
                    <img src="{{ asset('storage/' . $umkm->foto) }}" class="w-48 rounded-xl border">
                </div>
            @endif

            <div>
                <label class="block text-sm font-semibold mb-2">Ganti Foto</label>
                <input type="file" accept="image/jpeg,image/png,image/webp" name="foto" class="w-full rounded-xl border border-slate-300 p-3">
                <p class="text-xs text-slate-500 mt-2">
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 5MB.
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="publish" @selected(old('status', $umkm->status) == 'publish')>Publish</option>
                    <option value="draft" @selected(old('status', $umkm->status) == 'draft')>Draft</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Update
                </button>

                <a href="{{ route('admin.umkms.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
