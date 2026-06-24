<x-layouts.warga>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('warga.pengaduan.index') }}"
                   class="inline-flex mb-4 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                    Kembali
                </a>

                <p class="text-sm font-bold text-indigo-600">Warga Panel</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    Buat Pengaduan Baru
                </h1>
                <p class="text-slate-600 mt-2">
                    Data pelapor otomatis diambil dari akun warga Anda.
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <form action="{{ route('warga.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <p class="text-sm text-slate-500">Nama Pelapor</p>
                            <p class="font-bold">{{ $warga->nama }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">RT</p>
                            <p class="font-bold">RT {{ $warga->rt->nomor_rt ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">No HP</p>
                            <p class="font-bold">{{ $warga->no_hp ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">Alamat</p>
                            <p class="font-bold">{{ $warga->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Judul Pengaduan
                        </label>
                        <input type="text"
                               name="judul"
                               value="{{ old('judul') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Contoh: Lampu jalan mati"
                               required>

                        @error('judul')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Isi Pengaduan
                        </label>
                        <textarea name="isi"
                                  rows="5"
                                  class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Jelaskan pengaduan secara singkat dan jelas..."
                                  required>{{ old('isi') }}</textarea>

                        @error('isi')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Foto Lampiran <span class="text-slate-400">(opsional)</span>
                        </label>
                        <input type="file"
                               name="foto"
                               accept="image/jpeg,image/png,image/webp"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white">
                               <p class="text-xs text-slate-500 mt-2">
                                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 5MB.
                                </p>

                        @error('foto')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="w-full px-5 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700">
                        Kirim Pengaduan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.warga>
