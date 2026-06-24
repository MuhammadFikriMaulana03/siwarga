<x-layouts.admin title="Data Warga" header="Data Warga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold">Daftar Warga</h3>
                <p class="text-sm text-slate-500">Kelola data warga berdasarkan RT.</p>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold">Daftar Warga</h3>
                    <p class="text-sm text-slate-500">Kelola data warga berdasarkan RT.</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.wargas.import.form') }}"
                        class="px-4 py-2 rounded-xl bg-slate-800 text-white font-semibold hover:bg-slate-900">
                        Import CSV
                    </a>

                    <a href="{{ route('admin.wargas.export', request()->query()) }}"
                        class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                        Export CSV
                    </a>

                    <a href="{{ route('admin.wargas.create') }}"
                        class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                        + Tambah Warga
                    </a>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.wargas.index') }}" class="mb-6">
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="md:col-span-2">
                    <input type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, NIK, No KK, kepala keluarga, atau RT..."
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <select name="status_warga"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status Warga</option>
                        <option value="tetap" @selected(($statusWarga ?? '') === 'tetap')>Tetap</option>
                        <option value="kontrak" @selected(($statusWarga ?? '') === 'kontrak')>Kontrak</option>
                        <option value="pendatang" @selected(($statusWarga ?? '') === 'pendatang')>Pendatang</option>
                    </select>
                </div>

                <div>
                    <select name="status_aktif"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status Aktif</option>
                        <option value="1" @selected(($statusAktif ?? '') === '1')>Aktif</option>
                        <option value="0" @selected(($statusAktif ?? '') === '0')>Nonaktif</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold">
                        Filter
                    </button>

                    <a href="{{ route('admin.wargas.index') }}"
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
                        <th class="p-4">NIK</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">RT</th>
                        <th class="p-4">JK</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Aktif</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wargas as $warga)
                        <tr class="border-b">
                            <td class="p-4">{{ $loop->iteration }}</td>

                            <td class="p-4">
                                {{ $warga->kartuKeluarga->no_kk ?? $warga->no_kk ?? '-' }}
                            </td>

                            <td class="p-4">
                                {{ $warga->nik }}
                            </td>

                            <td class="p-4 font-semibold">
                                {{ $warga->nama }}
                            </td>

                            <td class="p-4">
                                RT {{ $warga->rt->nomor_rt ?? '-' }}
                            </td>

                            <td class="p-4">
                                {{ $warga->jenis_kelamin }}
                            </td>

                            <td class="p-4">
                                @php
                                    $statusWargaClass = match ($warga->status_warga) {
                                        'tetap' => 'bg-emerald-50 text-emerald-700',
                                        'kontrak' => 'bg-amber-50 text-amber-700',
                                        'pendatang' => 'bg-indigo-50 text-indigo-700',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusWargaClass }}">
                                    {{ ucfirst($warga->status_warga) }}
                                </span>
                            </td>

                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $warga->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $warga->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.wargas.edit', $warga) }}"
                                    class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                        Edit
                                    </a>

                                    <form   form action="{{ route('admin.wargas.destroy', $warga) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus data warga ini?')">
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
                                Belum ada data warga.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $wargas->links() }}
        </div>
    </div>
</x-layouts.admin>
