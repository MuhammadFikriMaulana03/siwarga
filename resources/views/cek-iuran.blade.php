<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Iuran Warga - SiWarga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                    S
                </div>
                <div>
                    <h1 class="font-bold leading-none">SiWarga</h1>
                    <p class="text-xs text-slate-500">Sistem Informasi RT/RW</p>
                </div>
            </a>

            @php
                $backUrl = auth()->check() && auth()->user()->role === 'warga'
                    ? route('warga.dashboard')
                    : route('landing');
            @endphp

            <a href="{{ $backUrl }}" class="px-4 py-2 rounded-xl bg-slate-100 font-semibold">
                Kembali
            </a>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
                <p class="text-sm font-bold text-indigo-600 mb-2">Iuran Warga</p>
                <h1 class="text-3xl font-extrabold mb-3">Cek Status Iuran</h1>
                <p class="text-slate-600 mb-8">
                    Masukkan NIK untuk melihat status pembayaran iuran warga.
                </p>

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('cek-iuran.result') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @csrf

                    <div class="md:col-span-3">
                        <input type="text"
                               name="nik"
                               value="{{ old('nik') }}"
                               placeholder="Masukkan NIK warga..."
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nik')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                        Cek Iuran
                    </button>
                </form>
            </div>

            @isset($warga)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-extrabold">{{ $warga->nama }}</h2>
                            <p class="text-sm text-slate-500 mt-1">
                                NIK {{ $warga->nik }} · RT {{ $warga->rt->nomor_rt ?? '-' }}
                            </p>
                            <p class="text-sm text-slate-500">
                                KK {{ $warga->kartuKeluarga->no_kk ?? $warga->no_kk ?? '-' }}
                            </p>
                        </div>

                        <span class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-sm">
                            {{ ucfirst($warga->status_warga) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
                        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5">
                            <p class="text-sm font-semibold text-indigo-700">Total Tagihan</p>
                            <h3 class="text-2xl font-extrabold text-indigo-700 mt-2">
                                Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                            </h3>
                        </div>

                        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
                            <p class="text-sm font-semibold text-emerald-700">Sudah Lunas</p>
                            <h3 class="text-2xl font-extrabold text-emerald-700 mt-2">
                                Rp {{ number_format($totalLunas, 0, ',', '.') }}
                            </h3>
                        </div>

                        <div class="rounded-2xl bg-red-50 border border-red-100 p-5">
                            <p class="text-sm font-semibold text-red-700">Belum Bayar</p>
                            <h3 class="text-2xl font-extrabold text-red-700 mt-2">
                                Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold">Riwayat Iuran</h3>
                        <p class="text-sm text-slate-500">Daftar tagihan dan pembayaran iuran warga.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b text-left">
                                    <th class="p-4">Periode</th>
                                    <th class="p-4">Nominal</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Tanggal Bayar</th>
                                    <th class="p-4">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($iurans as $item)
                                    <tr class="border-b">
                                        <td class="p-4">
                                            {{ \Carbon\Carbon::create()->month((int) $item->bulan)->translatedFormat('F') }}
                                            {{ $item->tahun }}
                                        </td>
                                        <td class="p-4 font-bold">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->status === 'lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                {{ $item->status === 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                            </span>
                                        </td>
                                        <td class="p-4">{{ $item->tanggal_bayar ?? '-' }}</td>
                                        <td class="p-4">{{ $item->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-slate-500">
                                            Belum ada data iuran untuk warga ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endisset
        </div>
    </main>
</body>
</html>
