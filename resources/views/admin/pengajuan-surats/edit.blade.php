<x-layouts.admin title="Proses Pengajuan Surat" header="Proses Pengajuan Surat">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-xl font-bold mb-2">
                {{ $pengajuanSurat->jenisSurat->nama ?? 'Pengajuan Surat' }}
            </h3>

            <p class="text-sm text-slate-500 mb-6">
                Diajukan oleh {{ $pengajuanSurat->nama_pemohon }} · {{ $pengajuanSurat->created_at->format('Y-m-d H:i') }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500">Nama Pemohon</p>
                    <p class="font-bold">{{ $pengajuanSurat->nama_pemohon }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500">NIK</p>
                    <p class="font-bold">{{ $pengajuanSurat->nik }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500">No HP</p>
                    <p class="font-bold">{{ $pengajuanSurat->no_hp ?? '-' }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="font-bold capitalize">{{ $pengajuanSurat->status }}</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 mb-5">
                <p class="text-sm text-slate-500 mb-1">Alamat</p>
                <p class="text-slate-700 whitespace-pre-line">{{ $pengajuanSurat->alamat ?? '-' }}</p>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-sm text-slate-500 mb-1">Keperluan</p>
                <p class="text-slate-700 whitespace-pre-line">{{ $pengajuanSurat->keperluan }}</p>
            </div>

            @if ($pengajuanSurat->no_hp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengajuanSurat->no_hp) }}"
                   target="_blank"
                   class="inline-flex mt-5 px-5 py-3 rounded-xl bg-emerald-50 text-emerald-700 font-bold">
                    Hubungi WhatsApp
                </a>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-lg mb-4">Update Pengajuan</h3>

            <form action="{{ route('admin.pengajuan-surats.update', $pengajuanSurat) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold mb-2">Jenis Surat</label>
                    <select name="jenis_surat_id" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($jenisSurats as $jenisSurat)
                            <option value="{{ $jenisSurat->id }}" @selected(old('jenis_surat_id', $pengajuanSurat->jenis_surat_id) == $jenisSurat->id)>
                                {{ $jenisSurat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="warga_id" value="{{ $pengajuanSurat->warga_id }}">
                <input type="hidden" name="nama_pemohon" value="{{ $pengajuanSurat->nama_pemohon }}">
                <input type="hidden" name="nik" value="{{ $pengajuanSurat->nik }}">
                <input type="hidden" name="no_hp" value="{{ $pengajuanSurat->no_hp }}">
                <input type="hidden" name="alamat" value="{{ $pengajuanSurat->alamat }}">
                <input type="hidden" name="keperluan" value="{{ $pengajuanSurat->keperluan }}">

                <div>
                    <label class="block text-sm font-semibold mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="menunggu" @selected(old('status', $pengajuanSurat->status) == 'menunggu')>Menunggu</option>
                        <option value="diproses" @selected(old('status', $pengajuanSurat->status) == 'diproses')>Diproses</option>
                        <option value="selesai" @selected(old('status', $pengajuanSurat->status) == 'selesai')>Selesai</option>
                        <option value="ditolak" @selected(old('status', $pengajuanSurat->status) == 'ditolak')>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Catatan Admin</label>
                    <textarea name="catatan_admin" rows="6"
                              class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan_admin', $pengajuanSurat->catatan_admin) }}</textarea>
                </div>

                <button class="w-full px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.pengajuan-surats.index') }}"
                   class="block text-center px-5 py-3 rounded-xl bg-slate-100 font-bold">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</x-layouts.admin>
