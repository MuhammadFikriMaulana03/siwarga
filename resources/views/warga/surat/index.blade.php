<x-layouts.warga>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm font-bold text-indigo-600">Warga Panel</p>
                    <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                        Surat Saya
                    </h1>

                    @if ($warga)
                        <p class="text-slate-600 mt-2">
                            Riwayat pengajuan surat milik <b>{{ $warga->nama }}</b>.
                        </p>
                    @else
                        <div class="mt-4 p-4 rounded-xl bg-red-50 text-red-700">
                            Akun warga ini belum dihubungkan dengan data warga.
                        </div>
                    @endif
                </div>

                @if ($warga)
                    <a href="{{ route('warga.surat.create') }}"
                       class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                        + Ajukan Surat Baru
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 text-emerald-700 px-5 py-4 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-2xl bg-red-50 text-red-700 px-5 py-4 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            @if ($warga)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 font-semibold">Total Pengajuan</p>
                        <h2 class="text-3xl font-extrabold text-indigo-600 mt-2">
                            {{ $totalPengajuan }}
                        </h2>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 font-semibold">Menunggu</p>
                        <h2 class="text-3xl font-extrabold text-amber-600 mt-2">
                            {{ $menunggu }}
                        </h2>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 font-semibold">Diproses</p>
                        <h2 class="text-3xl font-extrabold text-indigo-600 mt-2">
                            {{ $diproses }}
                        </h2>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 font-semibold">Selesai</p>
                        <h2 class="text-3xl font-extrabold text-emerald-600 mt-2">
                            {{ $selesai }}
                        </h2>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="text-lg font-bold">Riwayat Pengajuan Surat</h2>
                        <p class="text-sm text-slate-500">Filter berdasarkan kode, jenis surat, atau status.</p>

                        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-5">
                            @method('PUT')
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   placeholder="Cari kode / jenis surat..."
                                   class="md:col-span-2 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                            <select name="status"
                                    class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diproses" {{ $status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>

                            <div class="flex gap-2 md:col-span-2">
                                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                                    Filter
                                </button>

                                <a href="{{ route('warga.surat.index') }}"
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
                                    <th class="p-4">Jenis Surat</th>
                                    <th class="p-4">Keperluan</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Tanggal</th>
                                    <th class="p-4 text-right">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($pengajuanSurats as $item)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="p-4">
                                            {{ $pengajuanSurats->firstItem() + $loop->index }}
                                        </td>

                                        <td class="p-4 font-bold text-indigo-600">
                                            {{ $item->kode_tracking ?? '-' }}
                                        </td>

                                        <td class="p-4 font-semibold">
                                            {{ $item->jenisSurat->nama ?? '-' }}
                                        </td>

                                        <td class="p-4">
                                            {{ \Illuminate\Support\Str::limit($item->keperluan, 50) }}
                                        </td>

                                        <td class="p-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                                {{ $item->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : '' }}
                                                {{ $item->status === 'diproses' ? 'bg-indigo-50 text-indigo-700' : '' }}
                                                {{ $item->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                                {{ $item->status === 'ditolak' ? 'bg-red-50 text-red-700' : '' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>

                                        <td class="p-4">
                                            {{ $item->created_at?->format('Y-m-d') }}
                                        </td>

                                        <td class="p-4 text-right">
                                            <a href="{{ route('warga.surat.show', $item) }}"
                                               class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-8 text-center text-slate-500">
                                            Belum ada pengajuan surat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($pengajuanSurats instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="p-6">
                            {{ $pengajuanSurats->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-layouts.warga>
