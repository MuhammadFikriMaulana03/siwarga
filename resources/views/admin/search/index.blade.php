<x-layouts.admin header="Pencarian Global">
    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-bold text-indigo-600">Admin Panel</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                Pencarian Global
            </h1>
            <p class="text-slate-500 text-sm mt-2">
                Cari data warga, pengajuan surat, pengaduan, dan UMKM dari satu halaman.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                @method('PUT')
                <input type="text"
                       name="q"
                       value="{{ $q }}"
                       placeholder="Masukkan nama, NIK, No KK, kode tracking, atau kata kunci..."
                       class="flex-1 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                    Cari
                </button>

                <a href="{{ route('admin.search.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 text-center">
                    Reset
                </a>
            </form>
        </div>

        @if ($q === '')
            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-6 text-amber-700">
                Masukkan kata kunci pencarian terlebih dahulu.
            </div>
        @else
            <div class="mb-6 rounded-2xl bg-indigo-50 text-indigo-700 px-5 py-4 font-semibold">
                Hasil pencarian untuk: "{{ $q }}"
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                {{-- Warga --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="font-extrabold text-slate-900">Data Warga</h2>
                        <p class="text-sm text-slate-500">{{ $wargas->count() }} hasil ditemukan</p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($wargas as $warga)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-extrabold text-slate-900">{{ $warga->nama }}</p>
                                        <p class="text-sm text-slate-500">
                                            NIK {{ $warga->nik }} · KK {{ $warga->no_kk ?? $warga->kartuKeluarga->no_kk ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            RT {{ $warga->rt->nomor_rt ?? '-' }} · {{ $warga->alamat ?? '-' }}
                                        </p>
                                    </div>

                                    <a href="{{ route('admin.wargas.show', $warga) }}"
                                       class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-slate-500 text-sm">Tidak ada data warga.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Pengajuan Surat --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="font-extrabold text-slate-900">Pengajuan Surat</h2>
                        <p class="text-sm text-slate-500">{{ $pengajuanSurats->count() }} hasil ditemukan</p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($pengajuanSurats as $surat)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-extrabold text-slate-900">
                                            {{ $surat->jenisSurat->nama ?? 'Pengajuan Surat' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $surat->kode_tracking ?? '-' }} · {{ $surat->nama_pemohon }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Status: {{ ucfirst($surat->status) }}
                                        </p>
                                    </div>

                                    <a href="{{ route('admin.pengajuan-surats.edit', $surat) }}"
                                       class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-slate-500 text-sm">Tidak ada pengajuan surat.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Pengaduan --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="font-extrabold text-slate-900">Pengaduan</h2>
                        <p class="text-sm text-slate-500">{{ $pengaduans->count() }} hasil ditemukan</p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($pengaduans as $pengaduan)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-extrabold text-slate-900">{{ $pengaduan->judul }}</p>
                                        <p class="text-sm text-slate-500">
                                            {{ $pengaduan->kode_tracking ?? '-' }} · {{ $pengaduan->nama }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            RT {{ $pengaduan->rt->nomor_rt ?? '-' }} · Status: {{ ucfirst($pengaduan->status) }}
                                        </p>
                                    </div>

                                    <a href="{{ route('admin.pengaduans.edit', $pengaduan) }}"
                                       class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-slate-500 text-sm">Tidak ada pengaduan.</div>
                        @endforelse
                    </div>
                </div>

                {{-- UMKM --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="font-extrabold text-slate-900">UMKM Warga</h2>
                        <p class="text-sm text-slate-500">{{ $umkms->count() }} hasil ditemukan</p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($umkms as $umkm)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-extrabold text-slate-900">{{ $umkm->nama_usaha }}</p>
                                        <p class="text-sm text-slate-500">
                                            Pemilik: {{ $umkm->pemilik }} · {{ $umkm->kategori ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $umkm->alamat ?? '-' }}
                                        </p>
                                    </div>

                                    <a href="{{ route('admin.umkms.edit', $umkm) }}"
                                       class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-slate-500 text-sm">Tidak ada UMKM.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin>
