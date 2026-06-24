<x-layouts.warga>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('warga.surat.index') }}"
                   class="inline-flex mb-4 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                    Kembali
                </a>

                <p class="text-sm font-bold text-indigo-600">Detail Surat</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $pengajuanSurat->jenisSurat->nama ?? 'Pengajuan Surat' }}
                </h1>
                <p class="text-slate-600 mt-2">
                    Kode tracking: {{ $pengajuanSurat->kode_tracking ?? '-' }}
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-8">
                    <div>
                        <p class="text-sm text-slate-500">Nama Pemohon</p>
                        <p class="font-bold text-lg">{{ $pengajuanSurat->nama_pemohon }}</p>
                        <p class="text-sm text-slate-500">NIK {{ $pengajuanSurat->nik }}</p>
                    </div>

                    <span class="px-4 py-2 rounded-xl text-sm font-bold
                        {{ $pengajuanSurat->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : '' }}
                        {{ $pengajuanSurat->status === 'diproses' ? 'bg-indigo-50 text-indigo-700' : '' }}
                        {{ $pengajuanSurat->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $pengajuanSurat->status === 'ditolak' ? 'bg-red-50 text-red-700' : '' }}">
                        {{ ucfirst($pengajuanSurat->status) }}
                    </span>
                </div>

                <div class="space-y-5">
                    <div>
                        <p class="text-sm text-slate-500">Jenis Surat</p>
                        <p class="font-bold">{{ $pengajuanSurat->jenisSurat->nama ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Alamat</p>
                        <p class="font-bold">{{ $pengajuanSurat->alamat }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Keperluan</p>
                        <p class="text-slate-700 whitespace-pre-line">{{ $pengajuanSurat->keperluan }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Catatan Admin</p>
                        <p class="text-slate-700 whitespace-pre-line">
                            {{ $pengajuanSurat->catatan_admin ?? 'Belum ada catatan admin.' }}
                        </p>
                    </div>

                    @if ($pengajuanSurat->status === 'selesai')
                        <div class="pt-4">
                            <a href="{{ route('admin.pengajuan-surats.cetak', $pengajuanSurat) }}"
                               target="_blank"
                               class="inline-flex px-5 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700">
                                Download / Cetak Surat PDF
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.warga>
