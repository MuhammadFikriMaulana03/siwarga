<x-layouts.admin title="Detail Kartu Keluarga" header="Detail Kartu Keluarga">
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold mb-2">
                        KK {{ $kartuKeluarga->no_kk }}
                    </h3>

                    <p class="text-sm text-slate-500">
                        Detail kartu keluarga dan daftar anggota warga.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.kartu-keluargas.edit', $kartuKeluarga) }}"
                       class="px-4 py-2 rounded-xl bg-amber-50 text-amber-700 font-semibold">
                        Edit KK
                    </a>

                    <a href="{{ route('admin.kartu-keluargas.index') }}"
                       class="px-4 py-2 rounded-xl bg-slate-100 font-semibold">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-1">No KK</p>
                    <p class="font-bold">{{ $kartuKeluarga->no_kk }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-1">Kepala Keluarga</p>
                    <p class="font-bold">{{ $kartuKeluarga->kepala_keluarga }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-1">RT</p>
                    <p class="font-bold">RT {{ $kartuKeluarga->rt->nomor_rt ?? '-' }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-1">Jumlah Anggota</p>
                    <p class="font-bold">{{ $kartuKeluarga->wargas->count() }} orang</p>
                </div>
            </div>

            <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-sm text-slate-500 mb-1">Alamat</p>
                <p class="font-semibold">{{ $kartuKeluarga->alamat ?? '-' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold">Daftar Anggota Keluarga</h3>
                    <p class="text-sm text-slate-500">
                        Warga yang terhubung dengan kartu keluarga ini.
                    </p>
                </div>

                <a href="{{ route('admin.wargas.create') }}"
                   class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold">
                    + Tambah Warga
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-slate-50 text-left">
                            <th class="p-4">No</th>
                            <th class="p-4">NIK</th>
                            <th class="p-4">Nama</th>
                            <th class="p-4">JK</th>
                            <th class="p-4">Status Warga</th>
                            <th class="p-4">No HP</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kartuKeluarga->wargas as $warga)
                            <tr class="border-b">
                                <td class="p-4">{{ $loop->iteration }}</td>
                                <td class="p-4">{{ $warga->nik }}</td>
                                <td class="p-4 font-semibold">{{ $warga->nama }}</td>
                                <td class="p-4">{{ $warga->jenis_kelamin }}</td>
                                <td class="p-4 capitalize">{{ $warga->status_warga }}</td>
                                <td class="p-4">{{ $warga->no_hp ?? '-' }}</td>
                                <td class="p-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.wargas.edit', $warga) }}"
                                           class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-slate-500">
                                    Belum ada anggota keluarga.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
