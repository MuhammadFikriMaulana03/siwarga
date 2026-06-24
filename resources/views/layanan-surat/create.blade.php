<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat - SiWarga</title>
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
                <p class="text-sm font-bold text-indigo-600 mb-2">Layanan Surat</p>
                <h1 class="text-3xl font-extrabold mb-3">Ajukan Surat Online</h1>
                <p class="text-slate-600 mb-8">
                    Isi formulir berikut untuk mengajukan surat pengantar atau surat keterangan dari RT/RW.
                </p>

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('layanan-surat.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold mb-2">Jenis Surat</label>
                        <select name="jenis_surat_id"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih Jenis Surat</option>
                            @foreach ($jenisSurats as $jenisSurat)
                                <option value="{{ $jenisSurat->id }}" @selected(old('jenis_surat_id') == $jenisSurat->id)>
                                    {{ $jenisSurat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_surat_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nama_pemohon')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2">NIK</label>
                            <input type="text" name="nik" value="{{ old('nik') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nik')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">No HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Contoh: 08123456789">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Keperluan Surat</label>
                        <textarea name="keperluan" rows="5"
                                  class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Contoh: Untuk keperluan administrasi pembuatan rekening bank.">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
