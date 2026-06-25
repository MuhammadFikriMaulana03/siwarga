@props([
    'title' => null,
    'header' => 'Dashboard'
])

@php
    $settings = \App\Models\SystemSetting::allSettings();
    $pageTitle = $title ?? ($settings['rt_rw_name'] . ' - SiWarga');

    $pendingPengaduan = \App\Models\Pengaduan::where('status', 'masuk')->count();
    $pendingSurat = \App\Models\PengajuanSurat::where('status', 'menunggu')->count();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sidebar - Mobile: hidden by default */
        #sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        #sidebar.open {
            transform: translateX(0);
        }
        /* Desktop: visible by default, can be closed */
        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0);
            }
            #sidebar.closed {
                transform: translateX(-100%);
            }
        }
        /* Overlay - only for mobile */
        #sidebarOverlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }
        #sidebarOverlay.show {
            opacity: 1;
            pointer-events: auto;
        }
        /* Main content */
        #mainContent {
            transition: margin-left 0.3s ease-in-out;
        }
        @media (min-width: 1024px) {
            #mainContent {
                margin-left: 288px;
            }
            #mainContent.sidebar-closed {
                margin-left: 0;
            }
        }
        /* Hamburger animation */
        .hamburger-icon span {
            display: block;
            width: 20px;
            height: 2px;
            background: currentColor;
            transition: all 0.3s ease;
        }
        .hamburger-icon span:nth-child(1) { transform-origin: 0 0; }
        .hamburger-icon span:nth-child(3) { transform-origin: 0 100%; }
        .hamburger-active .hamburger-icon span:nth-child(1) {
            transform: rotate(45deg) translate(2px, -1px);
        }
        .hamburger-active .hamburger-icon span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger-active .hamburger-icon span:nth-child(3) {
            transform: rotate(-45deg) translate(2px, 1px);
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-900">
    <!-- Mobile Overlay -->
    <div id="sidebarOverlay"
         class="fixed inset-0 bg-black/50 z-30 lg:hidden"
         onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800 text-white flex flex-col shadow-2xl w-72">

        {{-- Logo Section --}}
        <div class="px-5 py-5 border-b border-slate-700/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tight">SiWarga</h1>
                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Admin Panel</p>
                    </div>
                </div>
                {{-- Hamburger di sidebar untuk mobile (tutup sidebar) --}}
                <button id="sidebarCloseBtn"
                        onclick="toggleSidebar()"
                        class="p-2 rounded-lg hover:bg-slate-800 transition lg:hidden">
                    <div class="hamburger-icon flex flex-col gap-1.5">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">

            {{-- Utama --}}
            <div class="mb-4">
                <p class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>

            {{-- Kelola Data --}}
            <div class="mb-4">
                <p class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kelola Data</p>

                <a href="{{ route('admin.wargas.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.wargas.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Data Warga</span>
                </a>

                <a href="{{ route('admin.rts.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.rts.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Data RT</span>
                </a>

                <a href="{{ route('admin.kartu-keluargas.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.kartu-keluargas.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Kartu Keluarga</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Akun User</span>
                </a>
            </div>

            {{-- Layanan --}}
            <div class="mb-4">
                <p class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Layanan</p>

                <a href="{{ route('admin.pengajuan-surats.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.pengajuan-surats.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Pengajuan Surat</span>
                    </div>
                    @if ($pendingSurat > 0)
                        <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-[10px] font-bold animate-pulse">
                            {{ $pendingSurat }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.jenis-surats.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.jenis-surats.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>Jenis Surat</span>
                </a>

                <a href="{{ route('admin.pengaduans.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.pengaduans.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Pengaduan</span>
                    </div>
                    @if ($pendingPengaduan > 0)
                        <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-[10px] font-bold animate-pulse">
                            {{ $pendingPengaduan }}
                        </span>
                    @endif
                </a>
            </div>

            {{-- Keuangan --}}
            <div class="mb-4">
                <p class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Keuangan</p>

                <a href="{{ route('admin.iuran-wargas.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.iuran-wargas.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Iuran Warga</span>
                </a>

                <a href="{{ route('admin.kas-transaksis.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.kas-transaksis.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Kas Transaksi</span>
                </a>
            </div>

            {{-- Inventaris --}}
<div class="mb-4">
    <p class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
        Inventaris
    </p>

    <a href="{{ route('admin.inventaris.index') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
       {{ request()->routeIs('admin.inventaris.*')
            ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25'
            : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">

        <svg class="w-5 h-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M20 7L12 3L4 7M20 7L12 11M20 7V17L12 21M4 7L12 11M4 7V17L12 21M12 11V21"/>
        </svg>

        <span>Inventaris RW</span>

    </a>
</div>

            {{-- Konten --}}
            <div class="mb-4">
                <p class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Konten</p>

                <a href="{{ route('admin.pengumuman.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.pengumuman.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Pengumuman</span>
                </a>

                <a href="{{ route('admin.kegiatans.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.kegiatans.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Kegiatan</span>
                </a>

                <a href="{{ route('admin.umkms.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.umkms.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>UMKM</span>
                </a>
            </div>

            {{-- Sistem --}}
            <div class="mb-4">
                <p class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Sistem</p>

                <a href="{{ route('admin.activity-logs.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.activity-logs.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Log Aktivitas</span>
                </a>

                <a href="{{ route('admin.backups.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.backups.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Backup</span>
                </a>

                <a href="{{ route('admin.settings.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group
                   {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-700 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </div>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-slate-700/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white font-bold hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div id="mainContent">
        {{-- Top Header --}}
        <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-lg border-b border-slate-200/80 shadow-sm">
            <div class="px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    {{-- Single Hamburger Button for all screens --}}
                    <button id="headerHamburger"
                            onclick="toggleSidebar()"
                            class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        <div class="hamburger-icon flex flex-col gap-1.5">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <div>
                        <h2 class="font-extrabold text-slate-900 truncate text-lg">{{ $header }}</h2>
                        <p class="text-xs sm:text-sm text-slate-500">{{ $settings['rt_rw_name'] }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('landing') }}"
                       class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Lihat Website
                    </a>

                    {{-- Profile Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button type="button"
                                @click="open = !open"
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-extrabold overflow-hidden shadow-lg ring-2 ring-transparent hover:ring-indigo-300 transition">
                            @if (auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </button>

                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-64 rounded-2xl bg-white border border-slate-100 shadow-2xl z-50 overflow-hidden"
                             style="display: none;">
                            <div class="p-4 bg-gradient-to-r from-slate-900 to-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-extrabold text-lg shadow-lg overflow-hidden shrink-0">
                                        @if (auth()->user()->profile_photo)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                                 alt="{{ auth()->user()->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold text-white truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 text-[10px] font-bold mt-1">
                                            Admin
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile-saya.edit') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">👤</span>
                                    <span>Edit Profile</span>
                                </a>
                                <div class="my-2 border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                                        <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">↪</span>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            const headerHamburger = document.getElementById('headerHamburger');
            const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                // Desktop: toggle between open and closed
                sidebar.classList.toggle('closed');
                mainContent.classList.toggle('sidebar-closed');
                headerHamburger.classList.toggle('hamburger-active');
            } else {
                // Mobile: toggle open/close
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
                headerHamburger.classList.toggle('hamburger-active');
                sidebarCloseBtn.classList.toggle('hamburger-active');
            }
        }
    </script>
</body>
</html>
