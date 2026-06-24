<x-layouts.warga>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('warga.surat.index') }}"
                   class="inline-flex mb-4 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                    Kembali
                </a>

                <p class="text-sm font-bold text-indigo-600">Warga Panel</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    Ajukan Surat Baru
                </h1>
                <p class="text-slate-600 mt-2">
                    Data pemohon otomatis diambil dari akun warga Anda.
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <form action="{{ route('warga.surat.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <p class="text-sm text-slate-500">Nama Pemohon</p>
                            <p class="font-bold">{{ $warga->nama }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">NIK</p>
                            <p class="font-bold">{{ $warga->nik }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">RT</p>
                            <p class="font-bold">RT {{ $warga->rt->nomor_rt ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">Alamat</p>
                            <p class="font-bold">{{ $warga->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Jenis Surat
                        </label>
                        <select name="jenis_surat_id"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="">Pilih Jenis Surat</option>
                            @foreach ($jenisSurats as $jenisSurat)
                                <option value="{{ $jenisSurat->id }}" {{ old('jenis_surat_id') == $jenisSurat->id ? 'selected' : '' }}>
                                    {{ $jenisSurat->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('jenis_surat_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            No HP
                        </label>
                        <input type="text"
                               name="no_hp"
                               value="{{ old('no_hp', $warga->no_hp) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Nomor HP aktif">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Keperluan
                        </label>
                        <textarea name="keperluan"
                                  rows="5"
                                  class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Contoh: Untuk keperluan administrasi kerja..."
                                  required>{{ old('keperluan') }}</textarea>

                        @error('keperluan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="w-full px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.warga>
