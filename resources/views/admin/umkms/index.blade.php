<x-layouts.admin title="UMKM Warga" header="UMKM Warga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold">Daftar UMKM Warga</h3>
                <p class="text-sm text-slate-500">Kelola data usaha warga di lingkungan RT/RW.</p>
            </div>

            <a href="{{ route('admin.umkms.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                + Tambah UMKM
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
                        <th class="p-4">Foto</th>
                        <th class="p-4">Nama Usaha</th>
                        <th class="p-4">Pemilik</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($umkms as $umkm)
                        <tr class="border-b">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4">
                                @if ($umkm->foto)
                                    <img src="{{ asset('storage/' . $umkm->foto) }}"
                                         class="w-20 h-14 object-cover rounded-lg border"
                                         alt="{{ $umkm->nama_usaha }}">
                                @else
                                    <div class="w-20 h-14 rounded-lg bg-slate-100 border flex items-center justify-center text-xs text-slate-400">
                                        No Image
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 font-semibold">{{ $umkm->nama_usaha }}</td>
                            <td class="p-4">{{ $umkm->pemilik ?? $umkm->warga->nama ?? '-' }}</td>
                            <td class="p-4">{{ $umkm->kategori ?? '-' }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $umkm->status === 'publish' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($umkm->status) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.umkms.edit', $umkm) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.umkms.destroy', $umkm) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus UMKM ini?')">
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
                                Belum ada data UMKM.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $umkms->links() }}
        </div>
    </div>
</x-layouts.admin>
