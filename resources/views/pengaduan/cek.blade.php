<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pengaduan - SiWarga</title>
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

            <a href="{{ route('landing') }}" class="px-4 py-2 rounded-xl bg-slate-100 font-semibold">
                Kembali
            </a>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
                <p class="text-sm font-bold text-indigo-600 mb-2">Pengaduan Warga</p>
                <h1 class="text-3xl font-extrabold mb-3">Cek Status Pengaduan</h1>
                <p class="text-slate-600 mb-8">
                    Masukkan kode tracking pengaduan Anda.
                </p>

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('pengaduan.cek.result') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @csrf

                    <div class="md:col-span-3">
                        <input type="text"
                               name="kode_tracking"
                               value="{{ old('kode_tracking') }}"
                               placeholder="Contoh: PGD-20260612-0001"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <button class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                        Cek Status
                    </button>
                </form>
            </div>

            @isset($pengaduan)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                        <div>
                            <p class="text-sm text-slate-500">Kode Tracking</p>
                            <h2 class="text-2xl font-extrabold text-indigo-600">
                                {{ $pengaduan->kode_tracking }}
                            </h2>
                        </div>

                        <span class="px-4 py-2 rounded-xl text-sm font-bold
                            {{ $pengaduan->status === 'masuk' ? 'bg-red-50 text-red-700' : '' }}
                            {{ $pengaduan->status === 'diproses' ? 'bg-indigo-50 text-indigo-700' : '' }}
                            {{ $pengaduan->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : '' }}
                            {{ $pengaduan->status === 'ditolak' ? 'bg-slate-100 text-slate-600' : '' }}">
                            {{ ucfirst($pengaduan->status) }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-500">Nama Pelapor</p>
                            <p class="font-bold">{{ $pengaduan->nama }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">Judul Pengaduan</p>
                            <p class="font-bold">{{ $pengaduan->judul }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">Isi Pengaduan</p>
                            <p class="text-slate-700 whitespace-pre-line">{{ $pengaduan->isi }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">Tanggapan Admin</p>
                            <p class="text-slate-700 whitespace-pre-line">
                                {{ $pengaduan->tanggapan ?? 'Belum ada tanggapan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endisset
        </div>
    </main>
</body>
</html>
