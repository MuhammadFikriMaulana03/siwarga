<x-layouts.admin title="Tambah Pengajuan Surat" header="Tambah Pengajuan Surat">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        <form action="{{ route('admin.pengajuan-surats.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Jenis Surat</label>
                <select name="jenis_surat_id" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Jenis Surat</option>
                    @foreach ($jenisSurats as $jenisSurat)
                        <option value="{{ $jenisSurat->id }}" @selected(old('jenis_surat_id') == $jenisSurat->id)>
                            {{ $jenisSurat->nama }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_surat_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Pilih dari Data Warga</label>
                <select name="warga_id" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tidak pilih / isi manual</option>
                    @foreach ($wargas as $warga)
                        <option value="{{ $warga->id }}" @selected(old('warga_id') == $warga->id)>
                            {{ $warga->nama }} - NIK {{ $warga->nik }} - RT {{ $warga->rt->nomor_rt ?? '-' }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">
                    Untuk sekarang, data pemohon tetap diisi manual dulu. Nanti bisa kita bikin auto-fill pakai JavaScript.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold mb-2">Nama Pemohon</label>
                    <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon') }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('nama_pemohon')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('nik')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">No HP / WhatsApp</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Alamat</label>
                <textarea name="alamat" rows="3"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Keperluan</label>
                <textarea name="keperluan" rows="5"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Contoh: Untuk keperluan administrasi pembuatan rekening bank.">{{ old('keperluan') }}</textarea>
                @error('keperluan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="menunggu" @selected(old('status') == 'menunggu')>Menunggu</option>
                    <option value="diproses" @selected(old('status') == 'diproses')>Diproses</option>
                    <option value="selesai" @selected(old('status') == 'selesai')>Selesai</option>
                    <option value="ditolak" @selected(old('status') == 'ditolak')>Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Catatan Admin</label>
                <textarea name="catatan_admin" rows="3"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan_admin') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Simpan
                </button>

                <a href="{{ route('admin.pengajuan-surats.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
