<x-layouts.warga>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-sm font-bold text-indigo-600">Warga Panel</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    Iuran Saya
                </h1>

                @if ($warga)
                    <p class="text-slate-600 mt-2">
                        Riwayat iuran milik <b>{{ $warga->nama }}</b>.
                    </p>
                @else
                    <div class="mt-4 p-4 rounded-xl bg-red-50 text-red-700">
                        Akun warga ini belum dihubungkan dengan data warga.
                    </div>
                @endif
            </div>

            @if ($warga)
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
                        <p class="text-sm text-slate-500">Filter riwayat iuran Anda.</p>

                        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-5">
                            @method('PUT')
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

                            <div class="flex gap-2 md:col-span-2">
                                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                                    Filter
                                </button>

                                <a href="{{ route('warga.iuran.index') }}"
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
                                    <th class="p-4">Periode</th>
                                    <th class="p-4">Nominal</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Tanggal Bayar</th>
                                    <th class="p-4">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($iurans as $item)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="p-4">
                                            {{ $iurans->firstItem() + $loop->index }}
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-slate-500">
                                            Belum ada data iuran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($iurans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="p-6">
                            {{ $iurans->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-layouts.warga>
