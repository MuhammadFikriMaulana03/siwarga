<x-layouts.warga>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-sm font-bold text-indigo-600">Dashboard Warga</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    Selamat Datang, {{ auth()->user()->name }}
                </h1>

                @if ($warga)
                    <p class="text-slate-600 mt-2">
                        Akun Anda terhubung dengan data warga: <b>{{ $warga->nama }}</b>.
                    </p>
                @else
                    <div class="mt-4 p-4 rounded-xl bg-red-50 text-red-700">
                        Akun warga ini belum dihubungkan dengan data warga.
                        Silakan hubungi admin RT/RW.
                    </div>
                @endif
            </div>

            @if ($warga)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-5 mb-8">
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 font-semibold">NIK</p>
                        <h2 class="text-lg font-extrabold text-slate-900 mt-2">
                            {{ $warga->nik }}
                        </h2>
                    </div>

                    <a href="{{ route('warga.iuran.index') }}"
                        class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-sm text-slate-500 font-semibold">Belum Bayar</p>
                        <h2 class="text-2xl font-extrabold text-red-600 mt-2">
                            Rp {{ number_format($iuranBelumBayar, 0, ',', '.') }}
                        </h2>
                        <p class="text-xs text-red-600 mt-2">
                            {{ $jumlahBelumBayar }} tagihan
                        </p>
                    </a>

                    <a href="{{ route('warga.iuran.index', ['status' => 'lunas']) }}"
                        class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-sm text-slate-500 font-semibold">Iuran Lunas</p>
                        <h2 class="text-2xl font-extrabold text-emerald-600 mt-2">
                            Rp {{ number_format($iuranLunas, 0, ',', '.') }}
                        </h2>
                    </a>

                    <a href="{{ route('warga.surat.index') }}"
                        class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-sm text-slate-500 font-semibold">Surat Saya</p>
                        <h2 class="text-3xl font-extrabold text-indigo-600 mt-2">
                            {{ $totalPengajuanSurat }}
                        </h2>
                        <p class="text-xs text-amber-600 mt-2">
                            {{ $pengajuanMenunggu }} menunggu
                        </p>
                    </a>

                    <a href="{{ route('warga.pengaduan.index') }}"
                        class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-sm text-slate-500 font-semibold">Pengaduan</p>
                        <h2 class="text-3xl font-extrabold text-red-600 mt-2">
                            {{ $totalPengaduan }}
                        </h2>
                        <p class="text-xs text-red-600 mt-2">
                        {{ $pengaduanMasuk }} masuk
                        </p>
                    </a>

                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 font-semibold">RT</p>
                        <h2 class="text-2xl font-extrabold text-indigo-600 mt-2">
                            RT {{ $warga->rt->nomor_rt ?? '-' }}
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <h2 class="text-lg font-bold">Data Diri</h2>
                            <p class="text-sm text-slate-500">Informasi data warga Anda.</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                            <div>
                                <p class="text-slate-500">Nama</p>
                                <p class="font-bold">{{ $warga->nama }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">No KK</p>
                                <p class="font-bold">{{ $warga->kartuKeluarga->no_kk ?? $warga->no_kk ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">Jenis Kelamin</p>
                                <p class="font-bold">{{ $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">No HP</p>
                                <p class="font-bold">{{ $warga->no_hp ?? '-' }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-slate-500">Alamat</p>
                                <p class="font-bold">{{ $warga->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <h2 class="text-lg font-bold">Iuran Terbaru</h2>
                            <p class="text-sm text-slate-500">Riwayat iuran warga Anda.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b text-left">
                                        <th class="p-4">Periode</th>
                                        <th class="p-4">Nominal</th>
                                        <th class="p-4">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($iuranTerbaru as $item)
                                        <tr class="border-b">
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-6 text-center text-slate-500">
                                                Belum ada data iuran.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">Surat Terbaru</h2>
                <p class="text-sm text-slate-500">Riwayat pengajuan surat Anda.</p>
            </div>

            <a href="{{ route('warga.surat.index') }}"
               class="text-sm font-bold text-indigo-600 hover:text-indigo-700">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b text-left">
                        <th class="p-4">Jenis</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pengajuanSuratTerbaru as $item)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="p-4">
                                <p class="font-bold">{{ $item->jenisSurat->nama ?? '-' }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $item->kode_tracking ?? '-' }}
                                </p>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-slate-500">
                                Belum ada pengajuan surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">Pengaduan Terbaru</h2>
                <p class="text-sm text-slate-500">Riwayat pengaduan Anda.</p>
            </div>

            <a href="{{ route('warga.pengaduan.index') }}"
               class="text-sm font-bold text-indigo-600 hover:text-indigo-700">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b text-left">
                        <th class="p-4">Judul</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pengaduanTerbaru as $item)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="p-4">
                                <p class="font-bold">{{ $item->judul }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $item->kode_tracking ?? '-' }}
                                </p>
                            </td>

                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $item->status === 'masuk' ? 'bg-red-50 text-red-700' : '' }}
                                    {{ $item->status === 'diproses' ? 'bg-indigo-50 text-indigo-700' : '' }}
                                    {{ $item->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $item->status === 'ditolak' ? 'bg-slate-100 text-slate-600' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td class="p-4">
                                {{ $item->created_at?->format('Y-m-d') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-slate-500">
                                Belum ada pengaduan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
            @endif
        </div>
    </div>
</x-layouts.warga>
