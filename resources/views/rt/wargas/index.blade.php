<x-layouts.rt>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm font-bold text-indigo-600">Ketua RT</p>
                    <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                        Data Warga RT {{ $rt->nomor_rt ?? '-' }}
                    </h1>
                    <p class="text-slate-600 mt-2">
                        Daftar warga yang terdaftar di RT Anda.
                    </p>
                </div>


            </div>

            @if (!$rt)
                <div class="bg-red-50 text-red-700 rounded-2xl p-5 mb-6">
                    Akun Ketua RT ini belum dihubungkan dengan data RT.
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold">Daftar Warga</h2>
                    <p class="text-sm text-slate-500">Cari berdasarkan nama, NIK, No KK, atau kepala keluarga.</p>

                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-5">
                        @method('PUT')
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari nama, NIK, No KK..."
                               class="md:col-span-2 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                        <select name="status_warga"
                                class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="tetap" {{ $statusWarga === 'tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="kontrak" {{ $statusWarga === 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="pendatang" {{ $statusWarga === 'pendatang' ? 'selected' : '' }}>Pendatang</option>
                        </select>

                        <select name="status_aktif"
                                class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Aktif</option>
                            <option value="1" {{ $statusAktif === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $statusAktif === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                                Filter
                            </button>

                            <a href="{{ route('rt.wargas.index') }}"
                               class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-left border-b">
                                <th class="p-4">No</th>
                                <th class="p-4">Nama</th>
                                <th class="p-4">NIK</th>
                                <th class="p-4">No KK</th>
                                <th class="p-4">JK</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Aktif</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wargas as $warga)
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="p-4">
                                        {{ $wargas->firstItem() + $loop->index }}
                                    </td>

                                    <td class="p-4">
                                        <p class="font-bold">{{ $warga->nama }}</p>
                                        <p class="text-xs text-slate-500">
                                            {{ $warga->alamat ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="p-4">{{ $warga->nik }}</td>

                                    <td class="p-4">
                                        {{ $warga->kartuKeluarga->no_kk ?? $warga->no_kk ?? '-' }}
                                    </td>

                                    <td class="p-4">
                                        {{ $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </td>

                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">
                                            {{ ucfirst($warga->status_warga) }}
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        @if ($warga->is_active)
                                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-right">
                                        <a href="{{ route('rt.wargas.show', $warga) }}"
                                           class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-slate-500">
                                        Belum ada data warga di RT ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($wargas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="p-6">
                        {{ $wargas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.rt>
