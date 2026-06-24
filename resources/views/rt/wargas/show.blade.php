<x-layouts.rt>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('rt.wargas.index') }}"
                   class="inline-flex mb-4 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                    Kembali
                </a>

                <p class="text-sm font-bold text-indigo-600">Detail Warga</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $warga->nama }}
                </h1>
                <p class="text-slate-600 mt-2">
                    Data warga RT {{ $warga->rt->nomor_rt ?? '-' }}.
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">NIK</p>
                        <p class="font-bold">{{ $warga->nik }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">No KK</p>
                        <p class="font-bold">{{ $warga->kartuKeluarga->no_kk ?? $warga->no_kk ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Tempat, Tanggal Lahir</p>
                        <p class="font-bold">
                            {{ $warga->tempat_lahir ?? '-' }},
                            {{ $warga->tanggal_lahir ? \Carbon\Carbon::parse($warga->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Jenis Kelamin</p>
                        <p class="font-bold">
                            {{ $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Agama</p>
                        <p class="font-bold">{{ $warga->agama ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Pekerjaan</p>
                        <p class="font-bold">{{ $warga->pekerjaan ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">No HP</p>
                        <p class="font-bold">{{ $warga->no_hp ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Status Warga</p>
                        <p class="font-bold">{{ ucfirst($warga->status_warga) }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-sm text-slate-500">Alamat</p>
                        <p class="font-bold">{{ $warga->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.rt>
