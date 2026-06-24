<x-layouts.admin title="Dashboard Admin RW" header="Dashboard">
    <div class="space-y-8">

        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Selamat Datang, Admin!</h1>
                <p class="text-sm text-slate-500 mt-1">Ringkasan data {{ $settings['rt_rw_name'] }}</p>
            </div>

            <form method="GET" class="flex items-end gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Bulan</label>
                    <select name="bulan" class="rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (int) $bulanIni === $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tahun</label>
                    <select name="tahun" class="rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @for ($year = now()->year + 1; $year >= now()->year - 5; $year--)
                            <option value="{{ $year }}" {{ (int) $tahunIni === $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                    Terapkan
                </button>
            </form>
        </div>

        {{-- Statistik Utama --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.wargas.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalWarga }}</p>
                        <p class="text-xs text-slate-500">Total Warga</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.rts.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalRt }}</p>
                        <p class="text-xs text-slate-500">Total RT</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.kartu-keluargas.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalKk }}</p>
                        <p class="text-xs text-slate-500">Kartu Keluarga</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.pengajuan-surats.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $pengajuanSurat }}</p>
                        <p class="text-xs text-slate-500">Pengajuan Surat</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.pengaduans.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-red-50 text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalPengaduan }}</p>
                        <p class="text-xs text-slate-500">Pengaduan</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.umkms.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-cyan-50 text-cyan-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalUmkm }}</p>
                        <p class="text-xs text-slate-500">UMKM</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Statistik Keuangan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.kas-transaksis.index') }}" class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl p-6 text-white hover:shadow-xl hover:shadow-indigo-500/30 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-indigo-200">Saldo Kas</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($saldoKas, 0, ',', '.') }}</p>
                        <p class="text-xs text-indigo-200 mt-2">
                            Masuk Rp {{ number_format($totalKasMasuk, 0, ',', '.') }} · Keluar Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.iuran-wargas.index', ['bulan' => $bulanIni, 'tahun' => $tahunIni]) }}" class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-6 text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-emerald-200">Iuran {{ \Carbon\Carbon::create()->month((int) $bulanIni)->translatedFormat('F') }}</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalIuranBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-emerald-200 mt-2">
                            Lunas Rp {{ number_format($iuranLunasBulanIni, 0, ',', '.') }} · Belum Rp {{ number_format($iuranBelumBayarBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.pengumuman.index') }}" class="bg-gradient-to-br from-violet-600 to-violet-700 rounded-2xl p-6 text-white hover:shadow-xl hover:shadow-violet-500/30 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-violet-200">Pengumuman & Kegiatan</p>
                        <p class="text-2xl font-bold mt-1">{{ $totalPengumuman + $totalKegiatan }}</p>
                        <p class="text-xs text-violet-200 mt-2">
                            {{ $totalPengumuman }} pengumuman · {{ $totalKegiatan }} kegiatan
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Grafik Analytics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Grafik Kas --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-bold text-slate-900 mb-4">Kas {{ \Carbon\Carbon::create()->month((int) $bulanIni)->translatedFormat('F') }}</h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-emerald-600 font-medium">Masuk</span>
                            <span class="font-semibold">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ max($persenKasMasuk, 5) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-red-600 font-medium">Keluar</span>
                            <span class="font-semibold">Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-red-500" style="width: {{ max($persenKasKeluar, 5) }}%"></div>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-600 font-medium">Saldo</span>
                            <span class="font-bold text-indigo-600">Rp {{ number_format($saldoKas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafik Iuran --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-bold text-slate-900 mb-4">Iuran Bulan Ini</h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-emerald-600 font-medium">Lunas</span>
                            <span class="font-semibold">Rp {{ number_format($iuranLunasBulanIni, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ max($persenIuranLunas, 5) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-red-600 font-medium">Belum Bayar</span>
                            <span class="font-semibold">Rp {{ number_format($iuranBelumBayarBulanIni, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-red-500" style="width: {{ max($persenIuranBelumBayar, 5) }}%"></div>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-600 font-medium">Total Tagihan</span>
                            <span class="font-bold text-indigo-600">Rp {{ number_format($totalIuranBulanIni, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafik Jenis Kelamin (Doughnut Chart) --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-bold text-slate-900 mb-4">Jenis Kelamin</h3>
                <div class="relative">
                    <canvas id="jkChart" class="max-h-40"></canvas>
                </div>
                <div class="flex items-center justify-center gap-4 mt-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <span class="text-xs text-slate-600">Laki-Laki: {{ $totalLakiLaki }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                        <span class="text-xs text-slate-600">Perempuan: {{ $totalPerempuan }}</span>
                    </div>
                </div>
            </div>

            {{-- Grafik Lansia (Doughnut Chart) --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-bold text-slate-900 mb-4">Data Lansia (60+)</h3>
                <div class="relative">
                    <canvas id="lansiaChart" class="max-h-40"></canvas>
                </div>
                <div class="flex items-center justify-center gap-4 mt-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-xs text-slate-600">Lansia: {{ $totalLansia }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-violet-500"></span>
                        <span class="text-xs text-slate-600">Non-Lansia: {{ $totalNonLansia }}</span>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            // Chart.js defaults
            Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
            Chart.defaults.font.size = 11;

            // Grafik Jenis Kelamin (Doughnut)
            const jkCtx = document.getElementById('jkChart').getContext('2d');
            new Chart(jkCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Laki-Laki', 'Perempuan'],
                    datasets: [{
                        data: [{{ $totalLakiLaki }}, {{ $totalPerempuan }}],
                        backgroundColor: ['#3b82f6', '#ec4899'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = {{ $totalLakiLaki + $totalPerempuan }};
                                    const value = context.raw;
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Grafik Lansia (Doughnut)
            const lansiaCtx = document.getElementById('lansiaChart').getContext('2d');
            new Chart(lansiaCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Lansia (60+)', 'Non-Lansia'],
                    datasets: [{
                        data: [{{ $totalLansia }}, {{ $totalNonLansia }}],
                        backgroundColor: ['#f59e0b', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = {{ $totalLansia + $totalNonLansia }};
                                    const value = context.raw;
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
        @endpush

        {{-- Aktivitas Terbaru --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Transaksi Kas --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-900">Transaksi Kas</h3>
                    <a href="{{ route('admin.kas-transaksis.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat semua</a>
                </div>
                <div class="space-y-2">
                    @forelse ($kasTerbaru as $item)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg {{ $item->tipe === 'masuk' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                    @if($item->tipe === 'masuk')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item->judul }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->tanggal }}</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold {{ $item->tipe === 'masuk' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $item->tipe === 'masuk' ? '+' : '-' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-4">Belum ada transaksi</p>
                    @endforelse
                </div>
            </div>

            {{-- Warga Terbaru --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-900">Warga Terbaru</h3>
                    <a href="{{ route('admin.wargas.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat semua</a>
                </div>
                <div class="space-y-2">
                    @forelse ($wargaTerbaru as $warga)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                {{ substr($warga->nama, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $warga->nama }}</p>
                                <p class="text-xs text-slate-500">NIK {{ substr($warga->nik, 0, 6) }}... · RT {{ $warga->rt->nomor_rt ?? '-' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-4">Belum ada data warga</p>
                    @endforelse
                </div>
            </div>

            {{-- Pengaduan --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-900">Pengaduan</h3>
                    <a href="{{ route('admin.pengaduans.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat semua</a>
                </div>
                <div class="space-y-2">
                    @forelse ($pengaduanTerbaru as $item)
                        <div class="p-3 rounded-xl bg-slate-50">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $item->judul }}</p>
                                    <p class="text-xs text-slate-500">Dari {{ $item->nama }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold capitalize
                                    @if($item->status === 'selesai') bg-emerald-100 text-emerald-700
                                    @elseif($item->status === 'diproses') bg-amber-100 text-amber-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ $item->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-4">Belum ada pengaduan</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
