<x-layouts.admin title="Edit Kegiatan" header="Edit Kegiatan">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        <form action="{{ route('admin.kegiatans.update', $kegiatan) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Judul Kegiatan</label>
                <input type="text" name="judul" value="{{ old('judul', $kegiatan->judul) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('judul')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="6"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $kegiatan->lokasi) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $kegiatan->jam_mulai) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $kegiatan->jam_selesai) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            @if ($kegiatan->gambar)
                <div>
                    <p class="text-sm font-semibold mb-2">Gambar Saat Ini</p>
                    <img src="{{ asset('storage/' . $kegiatan->gambar) }}" class="w-48 rounded-xl border">
                </div>
            @endif

            <div>
                <label class="block text-sm font-semibold mb-2">Ganti Gambar</label>
                <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp"
                       class="w-full rounded-xl border border-slate-300 p-3">

                       <p class="text-xs text-slate-500 mt-2">
                            Format JPG, JPEG, PNG, atau WEBP. Maksimal 5MB.
                        </p>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="publish" @selected(old('status', $kegiatan->status) == 'publish')>Publish</option>
                    <option value="draft" @selected(old('status', $kegiatan->status) == 'draft')>Draft</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Update
                </button>

                <a href="{{ route('admin.kegiatans.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
