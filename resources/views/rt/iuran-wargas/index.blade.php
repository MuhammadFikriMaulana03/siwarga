<x-layouts.rt>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm font-bold text-indigo-600">Ketua RT</p>
                    <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                        Iuran Warga RT {{ $rt->nomor_rt ?? '-' }}
                    </h1>
                    <p class="text-slate-600 mt-2">
                        Pantau status pembayaran iuran warga di RT Anda.
                    </p>
                </div>


            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 text-emerald-700 px-5 py-4 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if (!$rt)
                <div class="bg-red-50 text-red-700 rounded-2xl p-5 mb-6">
                    Akun Ketua RT ini belum dihubungkan dengan data RT.
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Total Tagihan</p>
                    <h2 class="text-2xl font-extrabold text-indigo-600 mt-2">
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Sudah Lunas</p>
                    <h2 class="text-2xl font-extrabold text-emerald-600 mt-2">
                        Rp {{ number_format($totalLunas, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Belum Bayar</p>
                    <h2 class="text-2xl font-extrabold text-red-600 mt-2">
                        Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold">Daftar Iuran</h2>
                    <p class="text-sm text-slate-500">
                        Cari berdasarkan nama warga, NIK, No KK, atau kepala keluarga.
                    </p>

                    <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3 mt-5">
                        @method('PUT')
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari nama, NIK, No KK..."
                               class="md:col-span-2 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                        <select name="status"
                                class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="lunas" {{ $status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum_bayar" {{ $status === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>

                        <select name="bulan"
                                class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ (int) $bulan === $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>

                        <select name="tahun"
                                class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Tahun</option>
                            @for ($year = now()->year + 1; $year >= now()->year - 5; $year--)
                                <option value="{{ $year }}" {{ (int) $tahun === $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                                Filter
                            </button>

                            <a href="{{ route('rt.iuran-wargas.index') }}"
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
                                <th class="p-4">Warga</th>
                                <th class="p-4">RT</th>
                                <th class="p-4">Periode</th>
                                <th class="p-4">Nominal</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Tanggal Bayar</th>
                                <th class="p-4">Keterangan</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($iuranWargas as $item)
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="p-4">
                                        {{ $iuranWargas->firstItem() + $loop->index }}
                                    </td>

                                    <td class="p-4">
                                        <p class="font-bold">{{ $item->warga->nama ?? '-' }}</p>
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
                                        {{ \Carbon\Carbon::create()->month((int) $item->bulan)->translatedFormat('F') }}
                                        {{ $item->tahun }}
                                    </td>

                                    <td class="p-4 font-bold">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>

                                    <td class="p-4">
                                        @if ($item->status === 'lunas')
                                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-4">
                                        {{ $item->tanggal_bayar ?? '-' }}
                                    </td>

                                    <td class="p-4">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>

                                    <td class="p-4 text-right">
                                        @if ($item->status === 'belum_bayar')
                                            <form action="{{ route('rt.iuran-wargas.tandai-lunas', $item) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tandai iuran ini sebagai lunas?')">
                                            @csrf
                                            @method('PATCH')

                                            <button class="px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-xs hover:bg-emerald-100">
                                                Tandai Lunas
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-8 text-center text-slate-500">
                                        Belum ada data iuran warga di RT ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($iuranWargas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="p-6">
                        {{ $iuranWargas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.rt>
