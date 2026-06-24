@props([
    'title' => null,
    'header' => 'Dashboard'
])

@php
    $settings = \App\Models\SystemSetting::allSettings();

    $pageTitle = $title ?? ('Warga ' . $settings['rt_rw_name'] . ' - SiWarga');

    $warga = auth()->user()->warga;
    $pendingSuratCount = $warga ? \App\Models\PengajuanSurat::where('warga_id', $warga->id)
        ->whereIn('status', ['menunggu', 'diproses'])
        ->count() : 0;
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
    <div class="min-h-screen">
        {{-- Desktop Sidebar --}}
        <aside class="hidden lg:flex fixed inset-y-0 left-0 z-40 w-72 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800 text-white flex-col shadow-2xl">
            {{-- Logo Section --}}
            <div class="px-6 py-6 border-b border-slate-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tight">SiWarga</h1>
                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Panel Warga</p>
                    </div>
                </div>
                <div class="mt-4 px-3 py-2 rounded-lg bg-slate-800/50 border border-slate-700/50">
                    <p class="text-xs text-slate-400">RT {{ $warga->rt->nama_rt ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">

                {{-- Section: Utama --}}
                <div class="mb-4">
                    <p class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Utama</p>

                    <a href="{{ route('warga.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 group
                       {{ request()->routeIs('warga.dashboard') ? 'bg-gradient-to-r from-amber-600 to-orange-700 text-white shadow-lg shadow-amber-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('warga.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                {{-- Section: Layanan --}}
                <div class="mb-4">
                    <p class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Layanan</p>

                    <a href="{{ route('warga.surat.index') }}"
                       class="flex items-center justify-between px-4 py-3 rounded-xl font-semibold transition-all duration-200 group
                       {{ request()->routeIs('warga.surat.*') ? 'bg-gradient-to-r from-amber-600 to-orange-700 text-white shadow-lg shadow-amber-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('warga.surat.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Surat</span>
                        </div>
                        @if ($pendingSuratCount > 0)
                            <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-bold animate-pulse">
                                {{ $pendingSuratCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('warga.pengaduan.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 group
                       {{ request()->routeIs('warga.pengaduan.*') ? 'bg-gradient-to-r from-amber-600 to-orange-700 text-white shadow-lg shadow-amber-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('warga.pengaduan.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Pengaduan</span>
                    </a>
                </div>

                {{-- Section: Keuangan --}}
                <div class="mb-4">
                    <p class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Keuangan</p>

                    <a href="{{ route('warga.iuran.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 group
                       {{ request()->routeIs('warga.iuran.*') ? 'bg-gradient-to-r from-amber-600 to-orange-700 text-white shadow-lg shadow-amber-500/25' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('warga.iuran.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Iuran</span>
                    </a>
                </div>
            </nav>

            {{-- User Profile & Logout --}}
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800/50 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center font-bold text-sm shadow-lg overflow-hidden">
                        @if (auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-full h-full object-cover rounded-full">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-400 truncate">Warga</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white font-bold hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-500/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Wrapper --}}
        <div class="lg:pl-72">
            {{-- Top Header --}}
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-lg border-b border-slate-200/80 shadow-sm">
                <div class="px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <h2 class="font-extrabold text-slate-900 truncate text-lg">
                            {{ $header }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 truncate">
                            Selamat datang, {{ auth()->user()->name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
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
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center font-extrabold overflow-hidden shadow-lg ring-2 ring-transparent hover:ring-amber-300 transition"
                                    title="Menu Profile">
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
                                 class="absolute right-0 mt-3 w-72 rounded-2xl bg-white border border-slate-100 shadow-2xl z-50 overflow-hidden"
                                 style="display: none;">
                                <div class="p-4 bg-gradient-to-r from-slate-900 to-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center font-extrabold text-lg shadow-lg overflow-hidden shrink-0">
                                            @if (auth()->user()->profile_photo)
                                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                                     alt="{{ auth()->user()->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-extrabold text-white truncate">
                                                {{ auth()->user()->name }}
                                            </p>
                                            <p class="text-xs text-slate-400 truncate">
                                                {{ auth()->user()->email }}
                                            </p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 text-[10px] font-bold mt-1">
                                                RT {{ $warga->rt->nama_rt ?? 'N/A' }}
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

                                    <a href="{{ route('warga.dashboard') }}"
                                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                        <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">🏠</span>
                                        <span>Dashboard</span>
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
            <main class="pb-24 lg:pb-8">
                {{ $slot }}
            </main>
        </div>

        {{-- Mobile Bottom Navigation --}}
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-slate-200 shadow-2xl">
            <div class="grid grid-cols-4">
                {{-- Home --}}
                <a href="{{ route('warga.dashboard') }}"
                   class="flex flex-col items-center justify-center gap-1 py-3 text-[11px] font-bold transition
                   {{ request()->routeIs('warga.dashboard') ? 'text-amber-600' : 'text-slate-500' }}">
                    <div class="p-2 rounded-xl {{ request()->routeIs('warga.dashboard') ? 'bg-amber-100' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 10.5L12 3l9 7.5M5.25 9.75V21h13.5V9.75" />
                        </svg>
                    </div>
                    <span>Home</span>
                </a>

                {{-- Surat --}}
                <a href="{{ route('warga.surat.index') }}"
                   class="relative flex flex-col items-center justify-center gap-1 py-3 text-[11px] font-bold transition
                   {{ request()->routeIs('warga.surat.*') ? 'text-amber-600' : 'text-slate-500' }}">
                    @if ($pendingSuratCount > 0)
                        <span class="absolute top-1 right-6 min-w-[18px] h-[18px] px-1 rounded-full bg-amber-500 text-white text-[10px] flex items-center justify-center leading-none animate-pulse">
                            {{ $pendingSuratCount }}
                        </span>
                    @endif
                    <div class="p-2 rounded-xl {{ request()->routeIs('warga.surat.*') ? 'bg-amber-100' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span>Surat</span>
                </a>

                {{-- Iuran --}}
                <a href="{{ route('warga.iuran.index') }}"
                   class="flex flex-col items-center justify-center gap-1 py-3 text-[11px] font-bold transition
                   {{ request()->routeIs('warga.iuran.*') ? 'text-amber-600' : 'text-slate-500' }}">
                    <div class="p-2 rounded-xl {{ request()->routeIs('warga.iuran.*') ? 'bg-amber-100' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span>Iuran</span>
                </a>

                {{-- Pengaduan --}}
                <a href="{{ route('warga.pengaduan.index') }}"
                   class="flex flex-col items-center justify-center gap-1 py-3 text-[11px] font-bold transition
                   {{ request()->routeIs('warga.pengaduan.*') ? 'text-amber-600' : 'text-slate-500' }}">
                    <div class="p-2 rounded-xl {{ request()->routeIs('warga.pengaduan.*') ? 'bg-amber-100' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <span>Aduan</span>
                </a>
            </div>
        </nav>
    </div>
</body>
</html>
