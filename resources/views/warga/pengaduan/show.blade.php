<x-layouts.warga>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('warga.pengaduan.index') }}"
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

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-8">
                    <div>
                        <p class="text-sm text-slate-500">Pelapor</p>
                        <p class="font-bold text-lg">{{ $pengaduan->nama }}</p>
                        <p class="text-sm text-slate-500">
                            RT {{ $pengaduan->rt->nomor_rt ?? '-' }}
                        </p>
                    </div>

                    <span class="px-4 py-2 rounded-xl text-sm font-bold
                        {{ $pengaduan->status === 'masuk' ? 'bg-red-50 text-red-700' : '' }}
                        {{ $pengaduan->status === 'diproses' ? 'bg-indigo-50 text-indigo-700' : '' }}
                        {{ $pengaduan->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $pengaduan->status === 'ditolak' ? 'bg-slate-100 text-slate-600' : '' }}">
                        {{ ucfirst($pengaduan->status) }}
                    </span>
                </div>

                <div class="space-y-5">
                    <div>
                        <p class="text-sm text-slate-500">Isi Pengaduan</p>
                        <p class="text-slate-700 whitespace-pre-line mt-2">
                            {{ $pengaduan->isi }}
                        </p>
                    </div>

                    @if ($pengaduan->foto)
                        <div>
                            <p class="text-sm text-slate-500 mb-2">Foto Lampiran</p>
                            <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                                 class="rounded-2xl max-h-96 border border-slate-100">
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-slate-500">Tanggapan Admin / Ketua RT</p>
                        <p class="text-slate-700 whitespace-pre-line mt-2">
                            {{ $pengaduan->tanggapan ?? 'Belum ada tanggapan.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.warga>
