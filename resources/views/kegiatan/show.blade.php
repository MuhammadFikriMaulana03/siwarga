<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kegiatan->judul }} - SiWarga</title>
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

            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}#kegiatan" class="px-4 py-2 rounded-xl bg-slate-100 font-semibold">
                    Kembali
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-5xl mx-auto px-6">
            <article class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                @if ($kegiatan->gambar)
                    <img src="{{ asset('storage/' . $kegiatan->gambar) }}"
                         alt="{{ $kegiatan->judul }}"
                         class="w-full h-80 object-cover">
                @endif

                <div class="p-8 md:p-10">
                    <div class="flex flex-wrap gap-3 mb-5">
                        <span class="px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm font-bold">
                            {{ $kegiatan->tanggal }}
                        </span>

                        @if ($kegiatan->jam_mulai)
                            <span class="px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold">
                                {{ substr($kegiatan->jam_mulai, 0, 5) }}
                                @if ($kegiatan->jam_selesai)
                                    - {{ substr($kegiatan->jam_selesai, 0, 5) }}
                                @endif
                            </span>
                        @endif

                        @if ($kegiatan->lokasi)
                            <span class="px-4 py-2 rounded-full bg-orange-50 text-orange-700 text-sm font-bold">
                                {{ $kegiatan->lokasi }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-6">
                        {{ $kegiatan->judul }}
                    </h1>

                    <div class="text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $kegiatan->deskripsi }}
                    </div>
                </div>
            </article>

            @if ($kegiatanLainnya->count())
                <section class="mt-12">
                    <h2 class="text-2xl font-extrabold mb-6">Kegiatan Lainnya</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($kegiatanLainnya as $item)
                            <a href="{{ route('kegiatan.show', $item) }}"
                               class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:-translate-y-1 transition">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}"
                                         alt="{{ $item->judul }}"
                                         class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-slate-100 flex items-center justify-center text-slate-400 text-sm">
                                        Tidak ada gambar
                                    </div>
                                @endif

                                <div class="p-5">
                                    <p class="text-xs text-slate-500 mb-2">
                                        {{ $item->tanggal }}
                                    </p>
                                    <h3 class="font-bold">{{ $item->judul }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>
</body>
</html>
