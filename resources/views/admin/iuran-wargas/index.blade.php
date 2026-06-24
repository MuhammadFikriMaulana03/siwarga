<x-layouts.admin title="Iuran Warga" header="Iuran Warga">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <p class="text-sm font-semibold text-slate-500">Total Tagihan</p>
                <h3 class="text-2xl font-extrabold mt-2 text-indigo-600">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </h3>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <p class="text-sm font-semibold text-slate-500">Sudah Lunas</p>
                <h3 class="text-2xl font-extrabold mt-2 text-emerald-600">
                    Rp {{ number_format($totalLunas, 0, ',', '.') }}
                </h3>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <p class="text-sm font-semibold text-slate-500">Belum Bayar</p>
                <h3 class="text-2xl font-extrabold mt-2 text-red-600">
                    Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold">Daftar Iuran Warga</h3>
                    <p class="text-sm text-slate-500">
                        Kelola status pembayaran iuran bulanan warga.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.iuran-wargas.pdf', request()->query()) }}"
                        target="_blank"
                        class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
                        Export PDF
                    </a>

                    <a href="{{ route('admin.iuran-wargas.export', request()->query()) }}"
                        class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                        Export CSV
                    </a>

                    <a href="{{ route('admin.iuran-wargas.generate.form') }}"
                        class="px-4 py-2 rounded-xl bg-slate-800 text-white font-semibold hover:bg-slate-900">
                        Generate Massal
                    </a>

                    <a href="{{ route('admin.iuran-wargas.create') }}"
                        class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                        + Tambah Iuran
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.iuran-wargas.index') }}" class="mb-6">
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                    <div class="md:col-span-2">
                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               placeholder="Cari nama, NIK, atau No KK..."
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <select name="status"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" @selected(($status ?? '') === 'belum_bayar')>Belum Bayar</option>
                            <option value="lunas" @selected(($status ?? '') === 'lunas')>Lunas</option>
                        </select>
                    </div>

                    <div>
                        <select name="bulan"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Bulan</option>
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}" @selected(($bulan ?? '') == $month)>
                                    {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="tahun"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Tahun</option>
                            @foreach (range(now()->year - 5, now()->year + 1) as $year)
                                <option value="{{ $year }}" @selected(($tahun ?? '') == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold">
                            Filter
                        </button>

                        <a href="{{ route('admin.iuran-wargas.index') }}"
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
                            <th class="p-4">Warga</th>
                            <th class="p-4">RT</th>
                            <th class="p-4">Periode</th>
                            <th class="p-4">Nominal</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Tanggal Bayar</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($iuranWargas as $item)
                            <tr class="border-b">
                                <td class="p-4">{{ $loop->iteration }}</td>

                                <td class="p-4">
                                    <p class="font-semibold">{{ $item->warga->nama ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">
                                        NIK {{ $item->warga->nik ?? '-' }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        KK {{ $item->warga->kartuKeluarga->no_kk ?? $item->warga->no_kk ?? '-' }}
                                    </p>
                                </td>

                                <td class="p-4">
                                    RT {{ $item->warga->rt->nomor_rt ?? '-' }}
                                </td>

                                <td class="p-4">
                                    {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}
                                    {{ $item->tahun }}
                                </td>

                                <td class="p-4 font-bold">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>

                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $item->status === 'lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $item->status === 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                    </span>
                                </td>

                                <td class="p-4">
                                    {{ $item->tanggal_bayar ?? '-' }}
                                </td>

                                <td class="p-4">
                                    <div class="flex justify-end gap-2">
                                        @if ($item->status === 'belum_bayar')
                                            <form action="{{ route('admin.iuran-wargas.tandai-lunas', array_merge([$item], request()->query())) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tandai iuran ini sebagai lunas? Transaksi kas masuk akan otomatis dibuat.')">
                                            @csrf
                                            @method('PATCH')

                                            <button class="px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 font-semibold">
                                                Tandai Lunas
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.iuran-wargas.edit', $item) }}"
                                        class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                            Edit
                                    </a>

                                    <form action="{{ route('admin.iuran-wargas.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus data iuran ini?')">
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
                                <td colspan="8" class="p-6 text-center text-slate-500">
                                    Belum ada data iuran warga.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $iuranWargas->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
