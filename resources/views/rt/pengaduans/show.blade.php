<x-layouts.rt>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('rt.pengaduans.index') }}"
                   class="inline-flex mb-4 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                    Kembali
                </a>

                <p class="text-sm font-bold text-indigo-600">Detail Pengaduan</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $pengaduan->judul }}
                </h1>
                <p class="text-slate-600 mt-2">
                    Kode tracking: {{ $pengaduan->kode_tracking ?? '-' }}
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 text-emerald-700 px-5 py-4 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                    <div class="mb-6">
                        <p class="text-sm text-slate-500">Pelapor</p>
                        <p class="font-bold">{{ $pengaduan->nama }}</p>
                        <p class="text-sm text-slate-500">{{ $pengaduan->no_hp ?? '-' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-slate-500">RT</p>
                        <p class="font-bold">RT {{ $pengaduan->rt->nomor_rt ?? '-' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-slate-500">Isi Pengaduan</p>
                        <p class="text-slate-700 whitespace-pre-line mt-2">
                            {{ $pengaduan->isi }}
                        </p>
                    </div>

                    @if ($pengaduan->foto)
                        <div class="mb-6">
                            <p class="text-sm text-slate-500 mb-2">Foto Lampiran</p>
                            <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                                 class="rounded-2xl max-h-96 border border-slate-100">
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-slate-500">Tanggapan Terakhir</p>
                        <p class="text-slate-700 whitespace-pre-line mt-2">
                            {{ $pengaduan->tanggapan ?? 'Belum ada tanggapan.' }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 h-fit">
                    <h2 class="text-lg font-bold mb-5">Update Status</h2>

                    <form action="{{ route('rt.pengaduans.update-status', $pengaduan) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Status
                            </label>
                            <select name="status"
                                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="masuk" {{ $pengaduan->status === 'masuk' ? 'selected' : '' }}>Masuk</option>
                                <option value="diproses" {{ $pengaduan->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $pengaduan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ $pengaduan->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Tanggapan
                            </label>
                            <textarea name="tanggapan"
                                      rows="5"
                                      class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Tulis tanggapan untuk warga...">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                        </div>

                        <button class="w-full px-4 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.rt>
