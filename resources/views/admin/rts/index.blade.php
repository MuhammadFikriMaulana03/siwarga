<x-layouts.admin title="Data RT" header="Data RT">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold">Daftar RT</h3>
                <p class="text-sm text-slate-500">Kelola data wilayah RT di lingkungan RW.</p>
            </div>

            <a href="{{ route('admin.rts.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                + Tambah RT
            </a>
        </div>

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
                        <th class="p-4">Nomor RT</th>
                        <th class="p-4">Ketua RT</th>
                        <th class="p-4">No HP</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rts as $rt)
                        <tr class="border-b">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-semibold">RT {{ $rt->nomor_rt }}</td>
                            <td class="p-4">{{ $rt->nama_ketua_rt ?? '-' }}</td>
                            <td class="p-4">{{ $rt->no_hp ?? '-' }}</td>
                            <td class="p-4">
                                @if ($rt->is_active)
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.rts.edit', $rt) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.rts.destroy', $rt) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus data RT ini?')">
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
                                Belum ada data RT.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $rts->links() }}
        </div>
    </div>
</x-layouts.admin>
