<x-layouts.rt>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm font-bold text-indigo-600">Ketua RT</p>
                    <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                        Pengaduan Warga RT {{ $rt->nomor_rt ?? '-' }}
                    </h1>
                    <p class="text-slate-600 mt-2">
                        Pantau dan tanggapi pengaduan warga di RT Anda.
                    </p>
                </div>


            </div>

            @if (!$rt)
                <div class="bg-red-50 text-red-700 rounded-2xl p-5 mb-6">
                    Akun Ketua RT ini belum dihubungkan dengan data RT.
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Total Pengaduan</p>
                    <h2 class="text-3xl font-extrabold text-indigo-600 mt-2">
                        {{ $totalPengaduan }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Masuk</p>
                    <h2 class="text-3xl font-extrabold text-red-600 mt-2">
                        {{ $pengaduanMasuk }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Diproses</p>
                    <h2 class="text-3xl font-extrabold text-indigo-600 mt-2">
                        {{ $pengaduanDiproses }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Selesai</p>
                    <h2 class="text-3xl font-extrabold text-emerald-600 mt-2">
                        {{ $pengaduanSelesai }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold">Daftar Pengaduan</h2>
                    <p class="text-sm text-slate-500">
                        Cari berdasarkan kode tracking, nama, judul, atau isi pengaduan.
                    </p>

                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-5">
                        @method('PUT')
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari kode, nama, judul..."
                               class="md:col-span-2 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                        <select name="status"
                                class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="masuk" {{ $status === 'masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="diproses" {{ $status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <div class="flex gap-2 md:col-span-2">
                            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                                Filter
                            </button>

                            <a href="{{ route('rt.pengaduans.index') }}"
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
                                <th class="p-4">Kode</th>
                                <th class="p-4">Pelapor</th>
                                <th class="p-4">Judul</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pengaduans as $pengaduan)
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="p-4">
                                        {{ $pengaduans->firstItem() + $loop->index }}
                                    </td>

                                    <td class="p-4 font-bold text-indigo-600">
                                        {{ $pengaduan->kode_tracking ?? '-' }}
                                    </td>

                                    <td class="p-4">
                                        <p class="font-bold">{{ $pengaduan->nama }}</p>
                                        <p class="text-xs text-slate-500">{{ $pengaduan->no_hp ?? '-' }}</p>
                                    </td>

                                    <td class="p-4">
                                        <p class="font-semibold">{{ $pengaduan->judul }}</p>
                                        <p class="text-xs text-slate-500 line-clamp-1">
                                            {{ $pengaduan->isi }}
                                        </p>
                                    </td>

                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                            {{ $pengaduan->status === 'masuk' ? 'bg-red-50 text-red-700' : '' }}
                                            {{ $pengaduan->status === 'diproses' ? 'bg-indigo-50 text-indigo-700' : '' }}
                                            {{ $pengaduan->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                            {{ $pengaduan->status === 'ditolak' ? 'bg-slate-100 text-slate-600' : '' }}">
                                            {{ ucfirst($pengaduan->status) }}
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        {{ $pengaduan->created_at?->format('Y-m-d') }}
                                    </td>

                                    <td class="p-4 text-right">
                                        <a href="{{ route('rt.pengaduans.show', $pengaduan) }}"
                                           class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-500">
                                        Belum ada pengaduan untuk RT ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($pengaduans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="p-6">
                        {{ $pengaduans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.rt>
