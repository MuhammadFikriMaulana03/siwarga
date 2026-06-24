<x-layouts.admin title="Pengaduan Warga" header="Pengaduan Warga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold">Daftar Pengaduan</h3>
            <p class="text-sm text-slate-500">Kelola laporan dan masukan dari warga.</p>
        </div>

        <form method="GET" action="{{ route('admin.pengaduans.index') }}" class="mb-6">
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <input type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, judul, atau isi pengaduan..."
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <select name="status"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="masuk" @selected(($status ?? '') === 'masuk')>Masuk</option>
                        <option value="diproses" @selected(($status ?? '') === 'diproses')>Diproses</option>
                        <option value="selesai" @selected(($status ?? '') === 'selesai')>Selesai</option>
                        <option value="ditolak" @selected(($status ?? '') === 'ditolak')>Ditolak</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold">
                        Filter
                    </button>

                    <a href="{{ route('admin.pengaduans.index') }}"
                    class="px-4 py-2 rounded-xl bg-slate-100 font-bold">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        @if (session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-slate-50 text-left">
                        <th class="p-4">No</th>
                        <th class="p-4">Kode</th>
                        <th class="p-4">RT</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Judul</th>
                        <th class="p-4">Isi Singkat</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengaduans as $pengaduan)
                        <tr class="border-b">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-semibold text-indigo-600">
                                {{ $pengaduan->kode_tracking ?? '-' }}
                            </td>
                            <td class="p-4">
                                RT {{ $pengaduan->rt->nomor_rt ?? '-' }}
                            </td>
                            <td class="p-4">{{ $pengaduan->nama }}</td>
                            <td class="p-4 font-semibold">{{ $pengaduan->judul }}</td>
                            <td class="p-4 text-slate-600">
                                {{ Str::limit($pengaduan->isi, 60) }}
                            </td>
                            <td class="p-4">
                                @php
                                    $statusClass = match ($pengaduan->status) {
                                        'masuk' => 'bg-red-50 text-red-700',
                                        'diproses' => 'bg-indigo-50 text-indigo-700',
                                        'selesai' => 'bg-emerald-50 text-emerald-700',
                                        'ditolak' => 'bg-slate-100 text-slate-600',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($pengaduan->status) }}
                                </span>
                            </td>
                            <td class="p-4">{{ $pengaduan->created_at->format('Y-m-d') }}</td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.pengaduans.edit', $pengaduan) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                        Proses
                                    </a>

                                    <form action="{{ route('admin.pengaduans.destroy', $pengaduan) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus pengaduan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 rounded-lg bg-red-50 text-red-700 font-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-slate-500">
                                Belum ada pengaduan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $pengaduans->links() }}
        </div>
    </div>
</x-layouts.admin>
