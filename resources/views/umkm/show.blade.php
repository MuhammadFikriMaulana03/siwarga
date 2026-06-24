<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $umkm->nama_usaha }} - SiWarga</title>
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
                <a href="{{ route('landing') }}#umkm" class="px-4 py-2 rounded-xl bg-slate-100 font-semibold">
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
                @if ($umkm->foto)
                    <img src="{{ asset('storage/' . $umkm->foto) }}"
                         alt="{{ $umkm->nama_usaha }}"
                         class="w-full h-80 object-cover">
                @endif

                <div class="p-8 md:p-10">
                    @if ($umkm->kategori)
                        <p class="inline-flex px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm font-bold mb-5">
                            {{ $umkm->kategori }}
                        </p>
                    @endif

                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4">
                        {{ $umkm->nama_usaha }}
                    </h1>

                    <p class="text-slate-500 mb-6">
                        Pemilik:
                        <span class="font-semibold text-slate-800">
                            {{ $umkm->pemilik ?? $umkm->warga->nama ?? '-' }}
                        </span>
                    </p>

                    @if ($umkm->deskripsi)
                        <div class="text-slate-700 leading-relaxed whitespace-pre-line mb-8">
                            {{ $umkm->deskripsi }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-sm text-slate-500 mb-1">Kontak</p>
                            <p class="font-bold">{{ $umkm->no_hp ?? '-' }}</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-sm text-slate-500 mb-1">Alamat Usaha</p>
                            <p class="font-bold">{{ $umkm->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($umkm->no_hp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->no_hp) }}"
                           target="_blank"
                           class="inline-flex px-6 py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700">
                            Hubungi via WhatsApp
                        </a>
                    @endif
                </div>
            </article>

            @if ($umkmLainnya->count())
                <section class="mt-12">
                    <h2 class="text-2xl font-extrabold mb-6">UMKM Lainnya</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($umkmLainnya as $item)
                            <a href="{{ route('umkm.show', $item) }}"
                               class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:-translate-y-1 transition">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}"
                                         alt="{{ $item->nama_usaha }}"
                                         class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-slate-100 flex items-center justify-center text-slate-400 text-sm">
                                        Tidak ada foto
                                    </div>
                                @endif

                                <div class="p-5">
                                    <p class="text-xs text-indigo-600 font-bold mb-2">
                                        {{ $item->kategori ?? 'UMKM Warga' }}
                                    </p>
                                    <h3 class="font-bold">{{ $item->nama_usaha }}</h3>
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
