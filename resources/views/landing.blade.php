@php
    $settings = $settings ?? \App\Models\SystemSetting::allSettings();

    $cekPengaduanUrl = \Illuminate\Support\Facades\Route::has('pengaduan.cek')
        ? route('pengaduan.cek')
        : url('/cek-pengaduan');

    $totalWarga = $totalWarga ?? 0;
    $totalRt = $totalRt ?? 0;
    $totalKk = $totalKk ?? 0;

    $pengumumanTerbaru = $pengumumanTerbaru ?? collect();
    $kegiatanTerbaru = $kegiatanTerbaru ?? collect();
    $umkmTerbaru = $umkmTerbaru ?? collect();
    $kasTerbaru = $kasTerbaru ?? collect();
    $daftarRtLanding = $daftarRtLanding ?? collect();

    $totalKasMasuk = $totalKasMasuk ?? 0;
    $totalKasKeluar = $totalKasKeluar ?? 0;
    $saldoKas = $saldoKas ?? 0;

    // Titik map default area Pamanukan. Bisa kamu ganti biar lebih akurat.
    $mapLat = (float) ($settings['map_lat'] ?? -6.286534);
    $mapLng = (float) ($settings['map_lng'] ?? 107.810943);
    $mapZoom = (int) ($settings['map_zoom'] ?? 15);

    $mapAddress = $settings['alamat_sekretariat'] ?? 'BTN Pamanukan Raya';

    // Polygon estimasi wilayah RW. Ganti koordinat ini kalau nanti punya batas resmi.
    $rwPolygon = [
        [-6.285650, 107.810073],
        [-6.286294, 107.810187],
        [-6.286342, 107.810571],
        [-6.287341, 107.810658],

        [-6.288733, 107.812627],
        [-6.287086, 107.812195],
        [-6.285283, 107.811774]
    ];
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiWarga - {{ $settings['rt_rw_name'] }}</title>

    <meta name="description" content="Portal layanan warga {{ $settings['rt_rw_name'] }} - pengajuan surat online, pengaduan warga, informasi kegiatan, UMKM, dan transparansi kas RT/RW.">

    {{-- Open Graph (preview link saat dishare ke WhatsApp/Facebook/dll) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="SiWarga - {{ $settings['rt_rw_name'] }}">
    <meta property="og:description" content="Portal layanan warga {{ $settings['rt_rw_name'] }} - surat, pengaduan, kegiatan, UMKM, dan kas RT/RW secara online.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="id_ID">
    @if (!empty($settings['logo']))
        <meta property="og:image" content="{{ asset('storage/' . $settings['logo']) }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SiWarga - {{ $settings['rt_rw_name'] }}">
    <meta name="twitter:description" content="Portal layanan warga {{ $settings['rt_rw_name'] }} - surat, pengaduan, kegiatan, UMKM, dan kas RT/RW secara online.">

    <link rel="canonical" href="{{ url()->current() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    {{--
        =====================================================================
        DARK MODE VARIABLES
        Semua warna dipusatkan di sini. Selector ".dark" di-toggle lewat JS
        di bagian bawah file (localStorage based).
        =====================================================================
    --}}
    <style>
        :root {
            --bg: #f8fafc;
            --bg-card: #ffffff;
            --bg-soft: #f1f5f9;
            --bg-navbar: rgba(255, 255, 255, 0.92);
            --bg-footer: #020617;

            --text: #0f172a;
            --text-soft: #64748b;
            --text-on-dark: #ffffff;

            --border: #e2e8f0;

            --friendly-blue: #1d4ed8;
            --friendly-green: #15803d;
            --friendly-red: #b91c1c;
            --friendly-dark: #0f172a;
        }

        .dark {
            --bg: #0d1117;
            --bg-card: #161b22;
            --bg-soft: #21262d;
            --bg-navbar: rgba(13, 17, 23, 0.92);
            --bg-footer: #010409;

            --text: #f0f6fc;
            --text-soft: #8b949e;
            --text-on-dark: #ffffff;

            --border: #30363d;
        }

        html,
        body,
        div,
        section,
        header,
        footer,
        nav,
        button,
        a {
            transition:
                background-color .35s ease,
                color .35s ease,
                border-color .35s ease,
                box-shadow .35s ease;
        }

        html, body {
            background: var(--bg);
            color: var(--text);
        }

        body {
            font-size: 18px;
            line-height: 1.7;
        }

        /* ============ Reusable theme utility classes ============ */
        .theme-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
        }

        .theme-text { color: var(--text); }
        .theme-soft { color: var(--text-soft); }
        .theme-border { border-color: var(--border); }
        .theme-bg-soft { background: var(--bg-soft); }

        .theme-navbar {
            background: var(--bg-navbar);
        }

        .easy-card {
            border: 1px solid var(--border);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        /* ============================================================
           GENERIC DARK-MODE OVERRIDES
           Menutup semua kelas Tailwind "mentah" yang dipakai langsung
           di markup (bg-white, text-slate-*, border-slate-*, dll) agar
           SELURUH section ikut berubah saat dark mode aktif, bukan
           cuma section yang sudah pakai theme-card / theme-text.
           ============================================================ */
        .dark { color-scheme: dark; }

        .dark .bg-white,
        .dark .bg-white\/85,
        .dark .bg-white\/90,
        .dark .bg-white\/95,
        .dark .bg-white\/10 {
            background-color: var(--bg-card) !important;
        }

        .dark .bg-stone-50,
        .dark .bg-slate-50,
        .dark .bg-slate-100 {
            background-color: var(--bg-soft) !important;
        }

        .dark .bg-indigo-50 { background-color: rgba(99, 102, 241, 0.16) !important; }
        .dark .bg-emerald-50 { background-color: rgba(16, 185, 129, 0.16) !important; }
        .dark .bg-red-50 { background-color: rgba(239, 68, 68, 0.16) !important; }
        .dark .bg-blue-50 { background-color: rgba(59, 130, 246, 0.16) !important; }

        .dark .text-slate-950,
        .dark .text-slate-900 {
            color: var(--text) !important;
        }

        .dark .text-slate-800,
        .dark .text-slate-700,
        .dark .text-slate-600 {
            color: var(--text-soft) !important;
        }

        .dark .border-slate-100,
        .dark .border-slate-200,
        .dark .border-slate-200\/70 {
            border-color: var(--border) !important;
        }

        .dark .bg-blue-100,
        .dark .text-blue-800 {
            color: #93c5fd !important;
        }

        .dark .border-blue-100 {
            border-color: rgba(59, 130, 246, 0.35) !important;
        }

        /* Section yang sengaja selalu gelap (hero footer, kontak, navbar gelap di top bar)
           biar tetap kontras dan nggak ikut "dipaksa terang" oleh aturan body */
        .always-dark, .always-dark * {
            color: var(--text-on-dark);
        }

        .quick-chip {
            border: 1px solid rgba(148, 163, 184, 0.28);
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(10px);
        }

        .dark .quick-chip {
            background: rgba(22, 27, 34, 0.85);
            border-color: var(--border);
            color: var(--text);
        }

        .easy-button {
            min-height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            font-size: 1.05rem;
            font-weight: 800;
            text-align: center;
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
        }

        .service-button {
            justify-content: flex-start;
            gap: 0.85rem;
        }

        .service-icon {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.18);
            font-size: 1rem;
            font-weight: 900;
            flex: 0 0 auto;
        }

        .easy-button:focus,
        a:focus,
        button:focus {
            outline: 4px solid #facc15;
            outline-offset: 3px;
        }

        [data-animate] {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 500ms ease, transform 500ms ease;
        }

        [data-animate].animate-show {
            opacity: 1;
            transform: translateY(0);
        }

        [data-animate="fade-left"] { transform: translateX(-28px); }
        [data-animate="fade-right"] { transform: translateX(28px); }
        [data-animate="zoom"] { transform: scale(0.96); }

        [data-animate="fade-left"].animate-show,
        [data-animate="fade-right"].animate-show {
            transform: translateX(0);
        }

        [data-animate="zoom"].animate-show { transform: scale(1); }

        .animate-delay-100 { transition-delay: 100ms; }
        .animate-delay-200 { transition-delay: 200ms; }
        .animate-delay-300 { transition-delay: 300ms; }

        #landingNavbar.navbar-scrolled {
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .hero-shell {
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.12), transparent 34%),
                linear-gradient(135deg, #ffffff 0%, #f8fafc 52%, #eef6ff 100%);
            border-color: var(--border);
        }

        .dark .hero-shell {
            background:
                radial-gradient(circle at top left, rgba(99, 102, 241, .15), transparent 35%),
                linear-gradient(135deg, #0d1117 0%, #161b22 50%, #1c2333 100%);
        }

        #rwMap {
            height: 430px;
            min-height: 430px;
            width: 100%;
            z-index: 1;
            background: #e5e7eb;
        }

        .leaflet-container {
            font-family: inherit;
            background: #e5e7eb;
        }

        .leaflet-control-attribution {
            font-size: 10px;
        }

        .rw-marker {
            width: 18px;
            height: 18px;
            background: #dc2626;
            border: 3px solid white;
            border-radius: 999px;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.35);
        }

        .mobile-menu-panel {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 220ms ease, opacity 180ms ease, padding 180ms ease;
        }

        .mobile-menu-panel.is-open {
            max-height: 420px;
            opacity: 1;
            padding-top: 0.75rem;
            padding-bottom: 1rem;
        }

        @media (max-width: 640px) {
            body { font-size: 16px; }

            #rwMap {
                height: 320px;
                min-height: 320px;
            }

            .easy-button {
                min-height: 54px;
                border-radius: 0.9rem;
                font-size: 0.98rem;
            }

            .service-icon {
                width: 2rem;
                height: 2rem;
                border-radius: 0.7rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            [data-animate] {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }
    </style>
</head>

<body class="antialiased">
    <div class="bg-slate-950 text-white always-dark">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-2.5">
            <div class="flex items-center justify-center sm:justify-between gap-3 text-center sm:text-left">
                <p class="text-xs sm:text-sm font-semibold text-slate-200">
                    Portal layanan warga {{ $settings['rt_rw_name'] }} — surat, pengaduan, info kas, dan kegiatan.
                </p>

                @if (!empty($settings['no_hp_rw']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['no_hp_rw']) }}"
                       target="_blank"
                       class="hidden sm:inline-flex text-xs font-bold text-emerald-300 hover:text-emerald-200">
                        WhatsApp Pengurus
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <header id="landingNavbar" class="theme-navbar sticky top-0 z-50 backdrop-blur border-b theme-border">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="h-16 flex items-center justify-between gap-3">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 min-w-0">
                    <div class="w-11 h-11 rounded-xl bg-blue-700 text-white flex items-center justify-center text-xl font-extrabold shadow-sm">
                        S
                    </div>

                    <div class="min-w-0">
                        <h1 class="text-xl font-extrabold leading-tight theme-text">SiWarga</h1>
                        <p class="text-sm theme-soft font-semibold truncate max-w-[190px] sm:max-w-none">
                            {{ $settings['rt_rw_name'] }} · {{ $settings['kelurahan'] }}
                        </p>
                    </div>
                </a>

                <div class="flex items-center gap-2">
                    <button id="themeToggle"
                            type="button"
                            aria-label="Ganti tema gelap/terang"
                            class="w-11 h-11 rounded-xl theme-card flex items-center justify-center hover:scale-105 transition-all duration-300">

                        <svg id="moonIcon" xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 theme-text"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>

                        <svg id="sunIcon" xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-yellow-400 hidden"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v2m0 14v2m9-9h-2M5 12H3 m15.364 6.364l-1.414-1.414 M7.05 7.05 5.636 5.636 m12.728 0-1.414 1.414 M7.05 16.95l-1.414 1.414 M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                    </button>

                    <button id="mobileMenuBtn"
                            type="button"
                            class="lg:hidden inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-base font-extrabold"
                            aria-controls="mobileMenuPanel"
                            aria-expanded="false">
                        Menu
                        <span id="mobileMenuIcon" aria-hidden="true">↓</span>
                    </button>
                </div>

                <nav aria-label="Menu utama desktop" class="hidden lg:flex gap-2 text-base font-bold">
                    <a href="#layanan" class="px-4 py-2.5 rounded-xl theme-text hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-700">Layanan</a>
                    <a href="#pengumuman" class="px-4 py-2.5 rounded-xl theme-text hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-700">Pengumuman</a>
                    <a href="#kontak" class="px-4 py-2.5 rounded-xl theme-text hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-700">Kontak</a>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800">Masuk</a>
                </nav>
            </div>

            <nav id="mobileMenuPanel" aria-label="Menu utama mobile" class="mobile-menu-panel lg:hidden border-t theme-border">
                <div class="grid grid-cols-1 gap-2 text-base font-bold">
                    <a href="#layanan" class="mobile-menu-link px-4 py-3 rounded-xl theme-bg-soft theme-text hover:opacity-80">Pilih Layanan</a>
                    <a href="#pengumuman" class="mobile-menu-link px-4 py-3 rounded-xl theme-bg-soft theme-text hover:opacity-80">Pengumuman</a>
                    <a href="#kontak" class="mobile-menu-link px-4 py-3 rounded-xl theme-bg-soft theme-text hover:opacity-80">Kontak Pengurus</a>
                    <a href="{{ route('dashboard') }}" class="mobile-menu-link px-4 py-3 rounded-xl bg-slate-900 text-white hover:bg-slate-800">Masuk Dashboard</a>
                </div>
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section id="beranda" class="relative overflow-hidden py-6 sm:py-12">
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6">
            <div class="hero-shell rounded-[1.8rem] sm:rounded-[2.5rem] border shadow-sm p-4 sm:p-8 lg:p-10">
                <div class="mb-6 grid grid-cols-3 gap-2 sm:flex sm:flex-wrap">
                    <div class="quick-chip rounded-2xl px-3 py-2 text-center sm:text-left">
                        <p class="text-[11px] sm:text-xs font-bold theme-soft leading-tight">Warga</p>
                        <p class="text-lg sm:text-xl font-black text-blue-700 leading-tight">{{ $totalWarga }}</p>
                    </div>
                    <div class="quick-chip rounded-2xl px-3 py-2 text-center sm:text-left">
                        <p class="text-[11px] sm:text-xs font-bold theme-soft leading-tight">KK</p>
                        <p class="text-lg sm:text-xl font-black text-blue-700 leading-tight">{{ $totalKk }}</p>
                    </div>
                    <div class="quick-chip rounded-2xl px-3 py-2 text-center sm:text-left">
                        <p class="text-[11px] sm:text-xs font-bold theme-soft leading-tight">RT</p>
                        <p class="text-lg sm:text-xl font-black text-blue-700 leading-tight">{{ $totalRt }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-[1.15fr_0.85fr] gap-8 xl:gap-10 items-start">
                    {{-- Left Content --}}
                    <div>
                        <div class="max-w-4xl" data-animate>
                            <span class="inline-flex px-4 py-2 rounded-full bg-blue-50 text-blue-800 text-sm sm:text-base font-extrabold mb-4 border border-blue-100">
                                Layanan warga {{ $settings['rt_rw_name'] }}
                            </span>

                            <h1 class="text-[2.15rem] sm:text-5xl lg:text-6xl font-black leading-[1.08] theme-text tracking-tight">
                                Mau urus apa hari ini?
                            </h1>

                            <p class="text-base sm:text-xl lg:text-2xl theme-soft mt-4 max-w-3xl leading-relaxed font-semibold">
                                Pilih layanan yang dibutuhkan. Dibuat sederhana supaya warga bisa mengurus keperluan tanpa harus datang bolak-balik.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-7">
                                <a href="{{ route('layanan-surat.create') }}"
                                   class="easy-button service-button w-full px-4 sm:px-5 py-4 bg-blue-700 text-white hover:bg-blue-800">
                                    <span class="service-icon">01</span>
                                    <span>Buat Surat</span>
                                </a>

                                <a href="{{ route('pengaduan.create') }}"
                                   class="easy-button service-button w-full px-4 sm:px-5 py-4 bg-red-700 text-white hover:bg-red-800">
                                    <span class="service-icon">02</span>
                                    <span>Lapor Masalah</span>
                                </a>

                                <a href="{{ $cekPengaduanUrl }}"
                                   class="easy-button service-button w-full px-4 sm:px-5 py-4 bg-slate-900 text-white hover:bg-slate-800">
                                    <span class="service-icon">03</span>
                                    <span>Cek Aduan</span>
                                </a>

                                <a href="#kontak"
                                   class="easy-button service-button w-full px-4 sm:px-5 py-4 bg-emerald-700 text-white hover:bg-emerald-800">
                                    <span class="service-icon">04</span>
                                    <span>Hubungi Pengurus</span>
                                </a>
                            </div>

                            <div class="mt-5 rounded-3xl theme-card p-4 sm:p-5">
                                <p class="text-base sm:text-lg font-extrabold theme-text mb-3">Alurnya singkat:</p>
                                <ol class="grid grid-cols-1 sm:grid-cols-3 gap-3 theme-soft font-semibold">
                                    <li class="rounded-2xl theme-bg-soft px-4 py-3"><span class="font-black text-blue-700">1.</span> Pilih layanan.</li>
                                    <li class="rounded-2xl theme-bg-soft px-4 py-3"><span class="font-black text-blue-700">2.</span> Isi data.</li>
                                    <li class="rounded-2xl theme-bg-soft px-4 py-3"><span class="font-black text-blue-700">3.</span> Tunggu diproses.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="mt-6 rounded-[2rem] theme-card p-5 sm:p-6" data-animate>
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div>
                                    <p class="text-base font-extrabold text-blue-700">Pengurus RT dan RW</p>
                                    <h3 class="text-2xl sm:text-3xl font-black theme-text mt-1">
                                        {{ $settings['rt_rw_name'] }}
                                    </h3>
                                </div>
                                <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 items-center justify-center font-extrabold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 5.197V19a6 6 0 00-6-6h-2a6 6 0 00-6 6v1" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Kartu RW --}}
                            <div class="mb-6">
                                <div class="rounded-2xl bg-gradient-to-r from-blue-900 to-blue-700 text-white p-5 sm:p-6">
                                    <div class="flex items-start gap-4 sm:gap-5">
                                        <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-white/20 flex items-center justify-center text-xl sm:text-2xl font-extrabold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs sm:text-sm font-bold text-blue-200">Ketua RW</p>
                                            <h4 class="text-xl sm:text-2xl font-black mt-1">{{ $settings['ketua_rw'] ?? 'Belum diatur' }}</h4>
                                            <p class="text-sm sm:text-base text-blue-100 mt-2">{{ $settings['rt_rw_name'] }}</p>
                                            @if ($settings['no_hp_rw'])
                                                <div class="mt-3">
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['no_hp_rw']) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-blue-900 text-sm font-bold hover:bg-blue-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                        Hubungi via WhatsApp
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Daftar RT --}}
                            <p class="text-lg font-extrabold theme-text mb-4">Ketua RT:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @forelse ($daftarRtLanding as $rt)
                                    <div class="rounded-2xl theme-card border-2 border-blue-100 p-5">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center font-extrabold text-lg">
                                                {{ str_pad($rt->nomor_rt, 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-lg text-blue-700 font-black">RT {{ str_pad($rt->nomor_rt, 2, '0', STR_PAD_LEFT) }}</p>
                                                <p class="text-xl font-black theme-text mt-1">
                                                    {{ $rt->nama_ketua_rt }}
                                                </p>
                                                @if ($rt->no_hp)
                                                    <p class="text-base theme-soft font-semibold mt-1">
                                                        Kontak: {{ $rt->no_hp }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl theme-bg-soft theme-border border p-4">
                                        <p class="text-xs theme-soft font-semibold">RT 01</p>
                                        <p class="text-lg font-extrabold theme-text mt-1">
                                            Nama Ketua RT01 belum diatur
                                        </p>
                                    </div>
                                    <div class="rounded-2xl theme-bg-soft theme-border border p-4">
                                        <p class="text-xs theme-soft font-semibold">RT 02</p>
                                        <p class="text-lg font-extrabold theme-text mt-1">
                                            Nama Ketua RT02 belum diatur
                                        </p>
                                    </div>
                                @endforelse
                            </div>

                            <p class="text-sm theme-soft mt-5 leading-relaxed">
                                Pengurus RT/RW siap membantu warga dalam pengurusan surat, pengaduan, dan kegiatan lingkungan.
                            </p>
                        </div>
                    </div>

                    {{-- Right Map --}}
                    <div class="lg:sticky lg:top-24" data-animate="fade-right">
                        <div class="theme-card rounded-[2rem] shadow-sm overflow-hidden">
                            <div class="p-5 sm:p-6 border-b theme-border">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-base font-extrabold text-blue-700">Peta Wilayah</p>
                                        <h2 class="text-2xl sm:text-3xl font-black theme-text mt-1">
                                            {{ $settings['rt_rw_name'] }}
                                        </h2>
                                        <p class="text-base theme-soft font-semibold mt-2">
                                            Area layanan warga sekitar {{ $mapAddress }}.
                                        </p>
                                    </div>

                                    <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 items-center justify-center font-extrabold">
                                        MAP
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="overflow-hidden rounded-3xl theme-border border">
                                    <div id="rwMap"></div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                    <div class="rounded-2xl theme-bg-soft theme-border border p-4">
                                        <p class="text-xs theme-soft font-semibold">Alamat Sekretariat</p>
                                        <p class="text-sm font-bold theme-text mt-1">
                                            {{ $mapAddress }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl theme-bg-soft theme-border border p-4">
                                        <p class="text-xs theme-soft font-semibold">Cakupan</p>
                                        <p class="text-sm font-bold theme-text mt-1">
                                            Wilayah {{ $settings['rt_rw_name'] }}
                                        </p>
                                    </div>
                                </div>

                                <a href="https://www.openstreetmap.org/?mlat={{ $mapLat }}&mlon={{ $mapLng }}#map={{ $mapZoom }}/{{ $mapLat }}/{{ $mapLng }}"
                                   target="_blank"
                                   class="easy-button mt-4 w-full px-5 py-3 bg-slate-900 text-white hover:bg-slate-800 transition">
                                    Buka Peta Lebih Besar
                                </a>

                                <p class="text-[11px] theme-soft mt-3 leading-relaxed">
                                    Catatan: area berwarna adalah ilustrasi estimasi wilayah. Untuk batas resmi, gunakan koordinat polygon RW yang valid.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan --}}
    <section id="layanan" class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="mb-10" data-animate>
                <p class="text-sm font-bold text-indigo-600">Layanan Warga</p>
                <h2 class="text-2xl sm:text-4xl font-extrabold theme-text mt-2">
                    Semua kebutuhan administrasi warga dalam satu tempat.
                </h2>
                <p class="theme-soft mt-3 max-w-2xl">
                    Warga dapat mengakses layanan surat, pengaduan, informasi kegiatan,
                    dan melihat transparansi kas lingkungan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="rounded-3xl theme-card p-6 shadow-sm hover:shadow-lg transition animate-delay-100" data-animate="zoom">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-extrabold mb-5">
                        1
                    </div>

                    <h3 class="text-lg font-extrabold theme-text">Pengajuan Surat</h3>
                    <p class="theme-soft text-sm mt-2 leading-relaxed">
                        Warga dapat mengajukan surat pengantar, domisili, keterangan usaha, dan lainnya secara online.
                    </p>

                    <a href="{{ route('layanan-surat.create') }}"
                       class="inline-flex mt-5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700">
                        Ajukan Surat
                    </a>
                </div>

                <div class="rounded-3xl theme-card p-6 shadow-sm hover:shadow-lg transition animate-delay-200" data-animate="zoom">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-extrabold mb-5">
                        2
                    </div>

                    <h3 class="text-lg font-extrabold theme-text">Pengaduan Warga</h3>
                    <p class="theme-soft text-sm mt-2 leading-relaxed">
                        Laporkan masalah lingkungan dan pantau status pengaduan menggunakan kode tracking.
                    </p>

                    <div class="flex flex-wrap gap-2 mt-5">
                        <a href="{{ route('pengaduan.create') }}"
                           class="inline-flex px-4 py-2 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700">
                            Kirim Pengaduan
                        </a>

                        <a href="{{ $cekPengaduanUrl }}"
                           class="inline-flex px-4 py-2 rounded-xl theme-bg-soft theme-text text-sm font-bold hover:opacity-80">
                            Cek Status
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl theme-card p-6 shadow-sm hover:shadow-lg transition animate-delay-300" data-animate="zoom">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-extrabold mb-5">
                        3
                    </div>

                    <h3 class="text-lg font-extrabold theme-text">Informasi RT/RW</h3>
                    <p class="theme-soft text-sm mt-2 leading-relaxed">
                        Lihat pengumuman, kegiatan, UMKM warga, dan laporan kas RT/RW secara transparan.
                    </p>

                    <a href="#pengumuman"
                       class="inline-flex mt-5 px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700">
                        Lihat Info
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Kegiatan --}}
    <section id="kegiatan" class="py-14 sm:py-20 theme-bg-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10" data-animate>
                <div>
                    <p class="text-sm font-bold text-indigo-600">Kegiatan</p>
                    <h2 class="text-2xl sm:text-4xl font-extrabold theme-text mt-2">
                        Kegiatan Terbaru
                    </h2>
                    <p class="theme-soft mt-3">
                        Agenda dan aktivitas warga yang dapat diikuti bersama.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse ($kegiatanTerbaru as $kegiatan)
                    <div class="theme-card rounded-3xl overflow-hidden hover:shadow-lg transition" data-animate="zoom">
                        @if ($kegiatan->gambar)
                            <img src="{{ asset('storage/' . $kegiatan->gambar) }}"
                                 alt="{{ $kegiatan->judul }}"
                                 loading="lazy"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="h-48 bg-indigo-50 flex items-center justify-center text-indigo-600 font-extrabold">
                                Kegiatan
                            </div>
                        @endif

                        <div class="p-6">
                            <p class="text-xs font-bold text-indigo-600 mb-2">
                                {{ $kegiatan->tanggal ? \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') : '-' }}
                            </p>

                            <h3 class="text-lg font-extrabold theme-text">
                                {{ $kegiatan->judul }}
                            </h3>

                            <p class="text-sm theme-soft mt-2 line-clamp-3">
                                {{ $kegiatan->deskripsi }}
                            </p>

                            <a href="{{ route('kegiatan.show', $kegiatan) }}"
                               class="inline-flex mt-5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3 theme-card rounded-3xl p-8 text-center theme-soft" data-animate>
                        Belum ada kegiatan yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- UMKM --}}
    <section id="umkm" class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="mb-10" data-animate>
                <p class="text-sm font-bold text-indigo-600">UMKM Warga</p>
                <h2 class="text-2xl sm:text-4xl font-extrabold theme-text mt-2">
                    Dukung Usaha Warga Sekitar
                </h2>
                <p class="theme-soft mt-3 max-w-2xl">
                    Temukan produk dan layanan dari warga sekitar {{ $settings['rt_rw_name'] }}.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse ($umkmTerbaru as $umkm)
                    <div class="theme-card rounded-3xl overflow-hidden hover:shadow-lg transition" data-animate="zoom">
                        @if ($umkm->foto)
                            <img src="{{ asset('storage/' . $umkm->foto) }}"
                                 alt="{{ $umkm->nama_usaha }}"
                                 loading="lazy"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="h-48 bg-emerald-50 flex items-center justify-center text-emerald-600 font-extrabold">
                                UMKM
                            </div>
                        @endif

                        <div class="p-6">
                            <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                {{ $umkm->kategori ?? 'UMKM' }}
                            </span>

                            <h3 class="text-lg font-extrabold theme-text mt-3">
                                {{ $umkm->nama_usaha }}
                            </h3>

                            <p class="text-sm theme-soft mt-1">
                                Pemilik: {{ $umkm->pemilik }}
                            </p>

                            <p class="text-sm theme-soft mt-2 line-clamp-3">
                                {{ $umkm->deskripsi }}
                            </p>

                            <div class="flex flex-wrap gap-2 mt-5">
                                <a href="{{ route('umkm.show', $umkm) }}"
                                   class="inline-flex px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700">
                                    Lihat Detail
                                </a>

                                @if ($umkm->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->no_hp) }}"
                                       target="_blank"
                                       class="inline-flex px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700">
                                        WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3 theme-card rounded-3xl p-8 text-center theme-soft" data-animate>
                        Belum ada UMKM yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Kas --}}
    <section id="kas" class="py-14 sm:py-20 theme-bg-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="mb-10" data-animate>
                <p class="text-sm font-bold text-indigo-600">Transparansi Kas</p>
                <h2 class="text-2xl sm:text-4xl font-extrabold theme-text mt-2">
                    Laporan Kas RT/RW
                </h2>
                <p class="theme-soft mt-3 max-w-2xl">
                    Ringkasan pemasukan dan pengeluaran kas lingkungan secara transparan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6" data-animate="zoom">
                <div class="rounded-3xl bg-emerald-50 border border-emerald-100 p-6">
                    <p class="text-sm font-bold text-emerald-700">Total Kas Masuk</p>
                    <p class="text-3xl font-extrabold text-emerald-700 mt-3">
                        Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-3xl bg-red-50 border border-red-100 p-6">
                    <p class="text-sm font-bold text-red-700">Total Kas Keluar</p>
                    <p class="text-3xl font-extrabold text-red-700 mt-3">
                        Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-3xl bg-indigo-50 border border-indigo-100 p-6">
                    <p class="text-sm font-bold text-indigo-700">Saldo Kas</p>
                    <p class="text-3xl font-extrabold text-indigo-700 mt-3">
                        Rp {{ number_format($saldoKas, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="theme-card rounded-3xl overflow-hidden" data-animate>
                <div class="p-6 border-b theme-border">
                    <h3 class="text-lg font-extrabold theme-text">Transaksi Kas Terbaru</h3>
                    <p class="text-sm theme-soft">Beberapa catatan transaksi kas terakhir.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[700px]">
                        <thead>
                            <tr class="theme-bg-soft text-left border-b theme-border">
                                <th class="p-4 theme-text">Tanggal</th>
                                <th class="p-4 theme-text">Judul</th>
                                <th class="p-4 theme-text">Kategori</th>
                                <th class="p-4 theme-text">Tipe</th>
                                <th class="p-4 text-right theme-text">Nominal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($kasTerbaru as $kas)
                                <tr class="border-b theme-border hover:bg-black/5 dark:hover:bg-white/5">
                                    <td class="p-4 theme-text">{{ $kas->tanggal }}</td>
                                    <td class="p-4 font-bold theme-text">{{ $kas->judul }}</td>
                                    <td class="p-4 theme-soft">{{ $kas->kategori }}</td>
                                    <td class="p-4">
                                        @if ($kas->tipe === 'masuk')
                                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                                Masuk
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                                Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right font-bold theme-text">
                                        Rp {{ number_format($kas->nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center theme-soft">
                                        Belum ada transaksi kas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Pengumuman --}}
    <section id="pengumuman" class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="mb-10" data-animate>
                <p class="text-sm font-bold text-indigo-600">Pengumuman</p>
                <h2 class="text-2xl sm:text-4xl font-extrabold theme-text mt-2">
                    Informasi Terbaru
                </h2>
                <p class="theme-soft mt-3 max-w-2xl">
                    Kabar penting dan pengumuman resmi untuk warga.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse ($pengumumanTerbaru as $pengumuman)
                    <div class="theme-card rounded-3xl overflow-hidden hover:shadow-lg transition" data-animate="zoom">
                        @if ($pengumuman->gambar)
                            <img src="{{ asset('storage/' . $pengumuman->gambar) }}"
                                 alt="{{ $pengumuman->judul }}"
                                 loading="lazy"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="h-48 bg-indigo-50 flex items-center justify-center text-indigo-600 font-extrabold">
                                Pengumuman
                            </div>
                        @endif

                        <div class="p-6">
                            <p class="text-xs font-bold text-indigo-600 mb-2">
                                {{ $pengumuman->tanggal_publish ? \Carbon\Carbon::parse($pengumuman->tanggal_publish)->translatedFormat('d F Y') : '-' }}
                            </p>

                            <h3 class="text-lg font-extrabold theme-text">
                                {{ $pengumuman->judul }}
                            </h3>

                            <p class="text-sm theme-soft mt-2 line-clamp-3">
                                {{ strip_tags($pengumuman->isi) }}
                            </p>

                            <a href="{{ route('pengumuman.show', $pengumuman) }}"
                               class="inline-flex mt-5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700">
                                Baca Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3 theme-card rounded-3xl p-8 text-center theme-soft" data-animate>
                        Belum ada pengumuman yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Kontak (selalu gelap, terlepas dari mode terang/gelap) --}}
    <section id="kontak" class="py-14 sm:py-20 bg-slate-950 text-white always-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                <div data-animate="fade-left">
                    <p class="text-sm font-bold text-indigo-300 mb-3">Kontak Pengurus</p>
                    <h2 class="text-3xl md:text-4xl font-extrabold">
                        Hubungi Pengurus {{ $settings['rt_rw_name'] }}
                    </h2>
                    <p class="text-slate-300 mt-4 leading-relaxed">
                        Untuk informasi layanan warga, pengajuan surat, iuran, kegiatan,
                        dan pengaduan lingkungan, silakan hubungi pengurus setempat.
                    </p>
                </div>

                <div class="bg-white/10 border border-white/10 rounded-3xl p-6 space-y-5" data-animate="fade-right">
                    <div>
                        <p class="text-sm text-slate-400">Nama Wilayah</p>
                        <p class="font-bold text-lg">{{ $settings['rt_rw_name'] }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Alamat Sekretariat</p>
                        <p class="font-bold">{{ $settings['alamat_sekretariat'] }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Kelurahan / Kecamatan</p>
                        <p class="font-bold">
                            {{ $settings['kelurahan'] }}, {{ $settings['kecamatan'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Kota/Kabupaten</p>
                        <p class="font-bold">{{ $settings['kota'] }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Ketua RW</p>
                        <p class="font-bold">{{ $settings['ketua_rw'] }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">No HP / WhatsApp</p>
                        <p class="font-bold">{{ $settings['no_hp_rw'] ?? '-' }}</p>
                    </div>

                    @if (!empty($settings['no_hp_rw']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['no_hp_rw']) }}"
                           target="_blank"
                           class="inline-flex px-5 py-3 rounded-xl bg-emerald-500 text-white font-bold hover:bg-emerald-600">
                            Hubungi via WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
<section id="faq" class="py-14 sm:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="mb-10 text-center" data-animate>
            <p class="text-sm font-bold text-indigo-600">FAQ</p>
            <h2 class="text-2xl sm:text-4xl font-extrabold theme-text mt-2">
                Pertanyaan yang Sering Ditanyakan
            </h2>
            <p class="theme-soft mt-3">
                Hal-hal yang biasanya warga tanyakan sebelum mengajukan layanan.
            </p>
        </div>

        <div class="space-y-3" data-animate>
            @php
                $faqList = [
                    [
                        'q' => 'Berapa lama proses pengajuan surat?',
                        'a' => 'Umumnya 1-3 hari kerja, tergantung jenis surat dan kelengkapan data. Status pengajuan bisa dipantau melalui menu Cek Aduan/status layanan.',
                    ],
                    [
                        'q' => 'Apakah harus datang langsung ke sekretariat?',
                        'a' => 'Tidak. Pengajuan surat dan pengaduan bisa dilakukan online lewat website ini. Kamu hanya perlu datang langsung jika diminta verifikasi dokumen asli oleh pengurus.',
                    ],
                    [
                        'q' => 'Bagaimana cara melacak status pengaduan saya?',
                        'a' => 'Setelah mengirim pengaduan, kamu akan mendapatkan kode tracking. Gunakan kode tersebut di halaman "Cek Aduan" untuk melihat status terbaru.',
                    ],
                    [
                        'q' => 'Apakah data kas RT/RW transparan untuk semua warga?',
                        'a' => 'Ya, ringkasan kas masuk, kas keluar, dan transaksi terbaru dapat dilihat oleh semua warga di section Transparansi Kas pada halaman ini.',
                    ],
                    [
                        'q' => 'Bagaimana cara mendaftarkan UMKM saya di halaman ini?',
                        'a' => 'Hubungi pengurus RT/RW setempat melalui WhatsApp pada bagian Kontak Pengurus untuk didaftarkan ke dalam sistem.',
                    ],
                ];
            @endphp

            @foreach ($faqList as $i => $faq)
                <details class="theme-card rounded-2xl p-5 group">
                    <summary class="flex items-center justify-between cursor-pointer font-bold theme-text list-none">
                        <span>{{ $faq['q'] }}</span>
                        <span class="ml-4 flex-shrink-0 w-7 h-7 rounded-full theme-bg-soft flex items-center justify-center text-sm font-black transition-transform group-open:rotate-45">
                            +
                        </span>
                    </summary>
                    <p class="theme-soft text-sm mt-3 leading-relaxed">
                        {{ $faq['a'] }}
                    </p>
                </details>
            @endforeach
        </div>
    </div>
</section>

    {{-- Footer (selalu gelap) --}}
    <footer class="border-t border-white/10 text-white py-8 always-dark" style="background: var(--bg-footer);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-extrabold">
                            S
                        </div>

                        <div>
                            <p class="font-extrabold">SiWarga</p>
                            <p class="text-sm text-slate-400">
                                {{ $settings['rt_rw_name'] }} · {{ $settings['kelurahan'] }} · {{ $settings['kota'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-slate-400">
                    © {{ date('Y') }} SiWarga. Sistem Informasi RT/RW.
                </p>
            </div>
        </div>
    </footer>

    {{-- Scroll To Top Button --}}
    <button id="scrollToTopBtn"
            type="button"
            aria-label="Kembali ke atas"
            class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-full bg-indigo-600 text-white shadow-2xl flex items-center justify-center text-xl font-extrabold opacity-0 pointer-events-none translate-y-4 transition-all duration-300 hover:bg-indigo-700">
        ↑
    </button>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{--
        =====================================================================
        DARK MODE TOGGLE SCRIPT
        Dijalankan SEBELUM body di-render sebenarnya tidak perlu — tapi
        supaya tidak "flash" warna terang sebelum JS jalan, idealnya skrip
        ini dipindah ke <head> (lihat catatan di bawah). Untuk simpelnya,
        di sini kita jalankan di awal DOMContentLoaded.
        =====================================================================
    --}}
    <script>
        (function () {
            // Terapkan tema secepat mungkin agar tidak ada "flash" putih.
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const animatedItems = document.querySelectorAll('[data-animate]');
            const navbar = document.getElementById('landingNavbar');
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenuPanel = document.getElementById('mobileMenuPanel');
            const mobileMenuIcon = document.getElementById('mobileMenuIcon');
            const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

            const showOnScroll = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-show');
                        showOnScroll.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            animatedItems.forEach(function (item) {
                showOnScroll.observe(item);
            });

            function handleScrollUI() {
                if (navbar) {
                    if (window.scrollY > 10) {
                        navbar.classList.add('navbar-scrolled');
                    } else {
                        navbar.classList.remove('navbar-scrolled');
                    }
                }

                if (scrollToTopBtn) {
                    if (window.scrollY > 400) {
                        scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                        scrollToTopBtn.classList.add('opacity-100', 'translate-y-0');
                    } else {
                        scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                        scrollToTopBtn.classList.remove('opacity-100', 'translate-y-0');
                    }
                }
            }

            if (mobileMenuBtn && mobileMenuPanel) {
                mobileMenuBtn.addEventListener('click', function () {
                    const isOpen = mobileMenuPanel.classList.toggle('is-open');
                    mobileMenuBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                    if (mobileMenuIcon) {
                        mobileMenuIcon.textContent = isOpen ? '↑' : '↓';
                    }
                });

                mobileMenuLinks.forEach(function (link) {
                    link.addEventListener('click', function () {
                        mobileMenuPanel.classList.remove('is-open');
                        mobileMenuBtn.setAttribute('aria-expanded', 'false');

                        if (mobileMenuIcon) {
                            mobileMenuIcon.textContent = '↓';
                        }
                    });
                });
            }

            if (scrollToTopBtn) {
                scrollToTopBtn.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            handleScrollUI();
            window.addEventListener('scroll', handleScrollUI);

            // ===================== Leaflet Map =====================
            const mapElement = document.getElementById('rwMap');

            if (mapElement && window.L) {
                const mapLat = {{ $mapLat }};
                const mapLng = {{ $mapLng }};
                const mapZoom = {{ $mapZoom }};
                const rwPolygon = @json($rwPolygon);

                const map = L.map('rwMap', {
                    scrollWheelZoom: false,
                    zoomControl: true,
                    attributionControl: true
                });

                const osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                });

                const cartoLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap &copy; CARTO'
                });

                cartoLayer.addTo(map);

                const areaPolygon = L.polygon(rwPolygon, {
                    color: '#dc2626',
                    weight: 4,
                    opacity: 1,
                    fillColor: '#ef4444',
                    fillOpacity: 0.18,
                    dashArray: '8, 6'
                }).addTo(map);

                const markerIcon = L.divIcon({
                    className: '',
                    html: '<div class="rw-marker"></div>',
                    iconSize: [18, 18],
                    iconAnchor: [9, 9]
                });

                const marker = L.marker([mapLat, mapLng], { icon: markerIcon }).addTo(map);

                areaPolygon.bindPopup(`
                    <strong>{{ $settings['rt_rw_name'] }}</strong><br>
                    Garis merah adalah estimasi area wilayah layanan warga.
                `);

                marker.bindPopup(`
                    <strong>Sekretariat {{ $settings['rt_rw_name'] }}</strong><br>
                    {{ $mapAddress }}
                `);

                map.fitBounds(areaPolygon.getBounds(), { padding: [28, 28] });

                L.control.layers({
                    'Peta Bersih': cartoLayer,
                    'OpenStreetMap': osmLayer
                }, {
                    'Estimasi Batas RW': areaPolygon,
                    'Sekretariat': marker
                }, { collapsed: true }).addTo(map);

                setTimeout(function () {
                    map.invalidateSize();
                    map.fitBounds(areaPolygon.getBounds(), { padding: [28, 28] });
                }, 500);

                setTimeout(function () {
                    map.invalidateSize();
                }, 1200);
            }

            // ===================== Dark Mode Toggle =====================
            const html = document.getElementById('htmlRoot');
            const toggle = document.getElementById('themeToggle');
            const moon = document.getElementById('moonIcon');
            const sun = document.getElementById('sunIcon');

            function updateThemeIcons(isDark) {
                if (isDark) {
                    moon.classList.add('hidden');
                    sun.classList.remove('hidden');
                } else {
                    moon.classList.remove('hidden');
                    sun.classList.add('hidden');
                }
            }

            // Sinkronkan ikon dengan kondisi tema saat ini (sudah di-set di <head>)
            updateThemeIcons(html.classList.contains('dark'));

            if (toggle) {
                toggle.addEventListener('click', function () {
                    const isDark = html.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    updateThemeIcons(isDark);
                });
            }
        });
    </script>
</body>
</html>
