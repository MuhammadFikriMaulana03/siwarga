<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Pengaduan - SiWarga</title>
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
        <div class="max-w-3xl mx-auto px-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <p class="text-sm font-bold text-indigo-600 mb-2">Pengaduan Warga</p>
                <h1 class="text-3xl font-extrabold mb-3">Kirim Pengaduan</h1>
                <p class="text-slate-600 mb-8">
                    Sampaikan laporan, keluhan, atau masukan terkait lingkungan RT/RW.
                </p>

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Pilih RT
                        </label>

                        <select name="rt_id"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="">Pilih RT</option>
                            @foreach ($rts as $rt)
                                <option value="{{ $rt->id }}" {{ old('rt_id') == $rt->id ? 'selected' : '' }}>
                                    RT {{ $rt->nomor_rt }} - {{ $rt->nama_ketua_rt ?? 'Ketua RT' }}
                                </option>
                            @endforeach
                        </select>

                        @error('rt_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nama')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">No HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Judul Pengaduan</label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('judul')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Isi Pengaduan</label>
                        <textarea name="isi" rows="6"
                                  class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('isi') }}</textarea>
                        @error('isi')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Foto Pendukung</label>
                        <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-slate-300 p-3">
                        <p class="text-xs text-slate-500 mt-2">
                            Format JPG, JPEG, PNG, atau WEBP. Maksimal 5MB.
                        </p>
                    </div>

                    <button class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                        Kirim Pengaduan
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
