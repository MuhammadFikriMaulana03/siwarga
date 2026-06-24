<x-layouts.admin title="Kartu Keluarga" header="Kartu Keluarga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold">Daftar Kartu Keluarga</h3>
                <p class="text-sm text-slate-500">Kelola data kartu keluarga warga RT/RW.</p>
            </div>

            <a href="{{ route('admin.kartu-keluargas.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                + Tambah KK
            </a>
        </div>

        <form method="GET" action="{{ route('admin.kartu-keluargas.index') }}" class="mb-6">
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <input type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari No KK, kepala keluarga, RT, atau alamat..."
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <select name="status_aktif"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="1" @selected(($statusAktif ?? '') === '1')>Aktif</option>
                        <option   option value="0" @selected(($statusAktif ?? '') === '0')>Nonaktif</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold">
                        Filter
                    </button>

                    <a href="{{ route('admin.kartu-keluargas.index') }}"
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
                        <th class="p-4">No KK</th>
                        <th class="p-4">Kepala Keluarga</th>
                        <th class="p-4">Anggota</th>
                        <th class="p-4">RT</th>
                        <th class="p-4">Alamat</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kartuKeluargas as $kk)
                        <tr class="border-b">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-semibold">{{ $kk->no_kk }}</td>
                            <td class="p-4">{{ $kk->kepala_keluarga }}</td>
                            <td class="p-4">
                            <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold">
                            {{ $kk->wargas_count }} orang
                            </span>
                            </td>
                            <td class="p-4">RT {{ $kk->rt->nomor_rt ?? '-' }}</td>
                            <td class="p-4">{{ $kk->alamat ?? '-' }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $kk->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $kk->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.kartu-keluargas.edit', $kk) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                        Edit
                                    </a>

                                    <a href="{{ route('admin.kartu-keluargas.show', $kk) }}"
                                        class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-semibold">
                                        Anggota ({{ $kk->wargas_count }})
                                    </a>

                                    <form action="{{ route('admin.kartu-keluargas.destroy', $kk) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus kartu keluarga ini?')">
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
                            <td colspan="7" class="p-6 text-center text-slate-500">
                                Belum ada data kartu keluarga.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $kartuKeluargas->links() }}
        </div>
    </div>
</x-layouts.admin>
