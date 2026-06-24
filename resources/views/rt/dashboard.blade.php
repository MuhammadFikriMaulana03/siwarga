<x-layouts.rt>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-sm font-bold text-indigo-600">Dashboard Ketua RT</p>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                    Selamat Datang, {{ auth()->user()->name }}
                </h1>

                @if ($rt)
                    <p class="text-slate-600 mt-2">
                        Anda mengelola data warga RT {{ $rt->nomor_rt }}.
                    </p>
                @else
                    <div class="mt-4 p-4 rounded-xl bg-red-50 text-red-700">
                        Akun Ketua RT ini belum dihubungkan dengan data RT.
                        Silakan hubungkan akun ini melalui database atau admin panel.
                    </div>
                @endif
            </div>

            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('rt.wargas.index') }}"
                    class="inline-flex px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                    Lihat Data Warga
                </a>

                <a href="{{ route('rt.iuran-wargas.index') }}"
                    class="inline-flex px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700">
                    Lihat Iuran Warga
                </a>

                <a href="{{ route('rt.pengaduans.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700">
                    <span>Lihat Pengaduan</span>

                    @if ($pengaduanMasukRt > 0)
                        <span class="px-2 py-1 rounded-full bg-white text-red-600 text-xs font-extrabold">
                            {{ $pengaduanMasukRt }}
                        </span>
                    @endif
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Total Warga</p>
                    <h2 class="text-3xl font-extrabold text-indigo-600 mt-2">
                        {{ $totalWarga }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Total KK</p>
                    <h2 class="text-3xl font-extrabold text-slate-800 mt-2">
                        {{ $totalKk }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Iuran Lunas Bulan Ini</p>
                    <h2 class="text-3xl font-extrabold text-emerald-600 mt-2">
                        Rp {{ number_format($totalIuranLunasBulanIni, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Belum Bayar</p>
                    <h2 class="text-3xl font-extrabold text-red-600 mt-2">
                        Rp {{ number_format($totalIuranBelumBayarBulanIni, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-red-600 mt-2">
                        {{ $jumlahBelumBayar }} warga belum bayar
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm text-slate-500 font-semibold">Pengaduan Masuk</p>
                    <h2 class="text-3xl font-extrabold text-red-600 mt-2">
                        {{ $pengaduanMasukRt }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-2">
                        Total pengaduan: {{ $totalPengaduanRt }}
                    </p>
                </div>
            </div>

            @if ($rt)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <h3 class="text-lg font-bold mb-4">Distribusi Jenis Kelamin</h3>
                    <div class="flex items-center justify-center">
                        <canvas id="jkChart" width="200" height="200"></canvas>
                    </div>
                    <div class="flex justify-center gap-6 mt-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span class="text-sm">Laki-laki: {{ $lakiLaki }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                            <span class="text-sm">Perempuan: {{ $perempuan }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <h3 class="text-lg font-bold mb-4">Distribusi Lansia</h3>
                    <div class="flex items-center justify-center">
                        <canvas id="lansiaChart" width="200" height="200"></canvas>
                    </div>
                    <div class="flex justify-center gap-6 mt-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="text-sm">Lansia (60+): {{ $lansia }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="text-sm">Non-Lansia: {{ $nonLansia }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold">Warga Terbaru</h3>
                    <p class="text-sm text-slate-500">Data warga terbaru di RT Anda.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-left border-b">
                                <th class="p-4">Nama</th>
                                <th class="p-4">NIK</th>
                                <th class="p-4">No KK</th>
                                <th class="p-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wargaTerbaru as $warga)
                                <tr class="border-b">
                                    <td class="p-4 font-bold">
                                        {{ $warga->nama }}
                                    </td>
                                    <td class="p-4">
                                        {{ $warga->nik }}
                                    </td>
                                    <td class="p-4">
                                        {{ $warga->kartuKeluarga->no_kk ?? $warga->no_kk ?? '-' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">
                                            {{ ucfirst($warga->status_warga) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-slate-500">
                                        Belum ada data warga untuk RT ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Jenis Kelamin
            const jkCtx = document.getElementById('jkChart');
            if (jkCtx) {
                new Chart(jkCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Laki-laki', 'Perempuan'],
                        datasets: [{
                            data: [{{ $lakiLaki }}, {{ $perempuan }}],
                            backgroundColor: ['#3b82f6', '#ec4899'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false }
                        },
                        cutout: '60%'
                    }
                });
            }

            // Chart Lansia
            const lansiaCtx = document.getElementById('lansiaChart');
            if (lansiaCtx) {
                new Chart(lansiaCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Lansia (60+)', 'Non-Lansia'],
                        datasets: [{
                            data: [{{ $lansia }}, {{ $nonLansia }}],
                            backgroundColor: ['#f59e0b', '#10b981'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false }
                        },
                        cutout: '60%'
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.rt>
