<x-layouts.admin title="Proses Pengaduan" header="Proses Pengaduan">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-xl font-bold mb-2">{{ $pengaduan->judul }}</h3>
            <p class="text-sm text-slate-500 mb-6">
                Dari {{ $pengaduan->nama }} · {{ $pengaduan->created_at->format('Y-m-d H:i') }}
            </p>

            @if ($pengaduan->foto)
                <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                     class="w-full max-h-96 object-cover rounded-2xl border mb-6">
            @endif

            <div class="p-5 rounded-2xl bg-slate-50 border text-slate-700 whitespace-pre-line">
                {{ $pengaduan->isi }}
            </div>

            @if ($pengaduan->no_hp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengaduan->no_hp) }}"
                   target="_blank"
                   class="inline-flex mt-5 px-5 py-3 rounded-xl bg-emerald-50 text-emerald-700 font-bold">
                    Hubungi WhatsApp
                </a>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-lg mb-4">Update Status</h3>

            <form action="{{ route('admin.pengaduans.update', $pengaduan) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="masuk" @selected($pengaduan->status === 'masuk')>Masuk</option>
                        <option value="diproses" @selected($pengaduan->status === 'diproses')>Diproses</option>
                        <option value="selesai" @selected($pengaduan->status === 'selesai')>Selesai</option>
                        <option value="ditolak" @selected($pengaduan->status === 'ditolak')>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Tanggapan Admin</label>
                    <textarea name="tanggapan" rows="6"
                              class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                </div>

                <button class="w-full px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.pengaduans.index') }}"
                   class="block text-center px-5 py-3 rounded-xl bg-slate-100 font-bold">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</x-layouts.admin>
