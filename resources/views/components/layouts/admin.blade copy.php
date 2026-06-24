@props([
    'title' => null,
    'header' => 'Dashboard'
])


@php

    $settings = \App\Models\SystemSetting::allSettings();
    $pageTitle = $title ?? ('Admin ' . $settings['rt_rw_name'] . ' - SiWarga');
    $sidebarPengajuanMenunggu = \App\Models\PengajuanSurat::where('status', 'menunggu')->count();
    $sidebarPengaduanMasuk = \App\Models\Pengaduan::where('status', 'masuk')->count();

    $sidebarIuranBelumBayar = \App\Models\IuranWarga::where('bulan', now()->month)
        ->where('tahun', now()->year)
        ->where('status', 'belum_bayar')
        ->count();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-slate-950 text-white hidden md:flex md:flex-col">
            <div class="px-6 py-5 border-b border-slate-800">
                <h1 class="text-2xl font-bold">SiWarga</h1>
                <p class="text-sm text-slate-400">Admin {{ $settings['rt_rw_name'] }}</p>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
    <a href="{{ route('admin.dashboard') }}"
       class="block px-4 py-3 rounded-xl font-medium
       {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        Dashboard
    </a>

    <a href="{{ route('admin.users.index') }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
         Kelola User
    </a>

    <a href="{{ route('admin.rts.index') }}"
       class="block px-4 py-3 rounded-xl font-medium
       {{ request()->routeIs('admin.rts.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        Data RT
    </a>

    <a href="{{ route('admin.wargas.index') }}"
       class="block px-4 py-3 rounded-xl font-medium
       {{ request()->routeIs('admin.wargas.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        Data Warga
    </a>

    <a href="{{ route('admin.kartu-keluargas.index') }}"
        class="block px-4 py-3 rounded-xl font-medium
            {{ request()->routeIs('admin.kartu-keluargas.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
            Kartu Keluarga
    </a>

    <a href="{{ route('admin.pengumuman.index') }}"
       class="block px-4 py-3 rounded-xl font-medium
       {{ request()->routeIs('admin.pengumuman.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        Pengumuman
    </a>

    <a href="{{ route('admin.kegiatans.index') }}"
       class="block px-4 py-3 rounded-xl font-medium
       {{ request()->routeIs('admin.kegiatans.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        Kegiatan
    </a>

    <a href="{{ route('admin.pengajuan-surats.index') }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.pengajuan-surats.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
            <span>Layanan Surat</span>

            @if ($sidebarPengajuanMenunggu > 0)
                <span class="px-2 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-bold">
                    {{ $sidebarPengajuanMenunggu }}
                </span>
            @endif
    </a>

    <a href="{{ route('admin.jenis-surats.index') }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.jenis-surats.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        Jenis Surat
    </a>

    <a href="{{ route('admin.kas-transaksis.index') }}"
        class="block px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.kas-transaksis.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        Iuran & Kas
    </a>

    <a href="{{ route('admin.iuran-wargas.index', ['bulan' => now()->month, 'tahun' => now()->year]) }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.iuran-wargas.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        <span>Iuran Warga</span>

        @if ($sidebarIuranBelumBayar > 0)
            <span class="px-2 py-1 rounded-full bg-red-500 text-white text-xs font-bold">
                {{ $sidebarIuranBelumBayar }}
            </span>
        @endif
    </a>

    <a href="{{ route('admin.pengaduans.index') }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.pengaduans.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
            <span>Pengaduan</span>

            @if ($sidebarPengaduanMasuk > 0)
                <span class="px-2 py-1 rounded-full bg-red-500 text-white text-xs font-bold">
                    {{ $sidebarPengaduanMasuk }}
                </span>
            @endif
        </a>

    <a href="{{ route('admin.umkms.index') }}"
       class="block px-4 py-3 rounded-xl font-medium
       {{ request()->routeIs('admin.umkms.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
        UMKM Warga
    </a>
    <a href="{{ route('admin.settings.edit') }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
            Pengaturan
    </a>
    <a href="{{ route('admin.backups.index') }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.backups.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
            Backup Data
    </a>
    <a href="{{ route('admin.activity-logs.index') }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl font-medium
        {{ request()->routeIs('admin.activity-logs.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200' }}">
            Log Aktivitas
    </a>
</nav>

            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <main class="flex-1">
            <!-- Topbar -->
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">{{ $header ?? 'Dashboard' }}</h2>
                    <p class="text-sm text-slate-500">Kelola informasi dan layanan warga RT/RW.</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <section class="p-6">
                {{ $slot }}
            </section>
        </main>
    </div>
</body>
</html>
