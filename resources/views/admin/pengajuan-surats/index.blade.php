<x-layouts.admin title="Layanan Surat" header="Layanan Surat">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold">Daftar Pengajuan Surat</h3>
                <p class="text-sm text-slate-500">Kelola pengajuan surat warga.</p>
            </div>

            <a href="{{ route('admin.pengajuan-surats.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                + Tambah Pengajuan
            </a>
        </div>

        <form method="GET" action="{{ route('admin.pengajuan-surats.index') }}" class="mb-6">
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <input type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, NIK, atau jenis surat..."
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <select name="status"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu" @selected(($status ?? '') === 'menunggu')>Menunggu</option>
                        <option value="diproses" @selected(($status ?? '') === 'diproses')>Diproses</option>
                        <option value="selesai" @selected(($status ?? '') === 'selesai')>Selesai</option>
                        <option value="ditolak" @selected(($status ?? '') === 'ditolak')>Ditolak</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold">
                        Filter
                    </button>

                    <a href="{{ route('admin.pengajuan-surats.index') }}"
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
                        <th class="p-4">Pemohon</th>
                        <th class="p-4">Jenis Surat</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuanSurats as $item)
                        <tr class="border-b">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4">
                                <p class="font-semibold">{{ $item->nama_pemohon }}</p>
                                <p class="text-xs text-slate-500">NIK {{ $item->nik }}</p>
                            </td>
                            <td class="p-4">{{ $item->jenisSurat->nama ?? '-' }}</td>
                            <td class="p-4">
                                @php
                                    $statusClass = match ($item->status) {
                                    'menunggu' => 'bg-amber-50 text-amber-700',
                                    'diproses' => 'bg-indigo-50 text-indigo-700',
                                    'selesai' => 'bg-emerald-50 text-emerald-700',
                                    'ditolak' => 'bg-red-50 text-red-700',
                                    default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="p-4">{{ $item->created_at->format('Y-m-d') }}</td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.pengajuan-surats.edit', $item) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                        Proses
                                    </a>
                                    @if ($item->status === 'selesai')
                                        <a href="{{ route('admin.pengajuan-surats.cetak', $item) }}"
                                            target="_blank"
                                            class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-semibold">
                                            Cetak
                                        </a>
                                    @endif

                                    <form action="{{ route('admin.pengajuan-surats.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus pengajuan ini?')">
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
                            <td colspan="6" class="p-6 text-center text-slate-500">
                                Belum ada pengajuan surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $pengajuanSurats->links() }}
        </div>
    </div>
</x-layouts.admin>
