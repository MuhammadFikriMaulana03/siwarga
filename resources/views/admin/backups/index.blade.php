<x-layouts.admin>
    <div class="p-6 max-w-4xl">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-slate-900">Backup Data</h1>
            <p class="text-slate-500 text-sm">
                Download cadangan database SiWarga dalam format SQL.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-2xl bg-emerald-50 text-emerald-700 px-5 py-4 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-2xl bg-red-50 text-red-700 px-5 py-4 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">
                        Backup Database MySQL
                    </h2>
                    <p class="text-slate-500 mt-2">
                        Gunakan fitur ini sebelum melakukan perubahan besar, deploy, atau import data.
                    </p>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                            <p class="text-slate-500">Database</p>
                            <p class="font-bold">{{ config('database.connections.mysql.database') }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                            <p class="text-slate-500">Waktu Backup</p>
                            <p class="font-bold">{{ now()->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.backups.download') }}"
                   class="inline-flex justify-center px-6 py-4 rounded-2xl bg-indigo-600 text-white font-extrabold hover:bg-indigo-700">
                    Download Backup
                </a>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <div class="mb-6">
                <h2 class="text-xl font-extrabold text-slate-900">
                    Restore Database
                </h2>
                <p class="text-slate-500 mt-2">
                    Upload file backup SQL untuk mengembalikan data database.
                </p>
            </div>

            <div class="mb-6 rounded-2xl bg-red-50 border border-red-100 p-5 text-red-700">
                <h3 class="font-extrabold mb-2">Peringatan</h3>
                <p class="text-sm leading-relaxed">
                    Restore database bisa menimpa data yang sedang ada. Pastikan Anda sudah download backup terbaru sebelum restore.
                </p>
            </div>

            <form action="{{ route('admin.backups.restore') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-5"
                onsubmit="return confirm('Yakin ingin restore database? Data saat ini bisa berubah sesuai file backup.')">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        File Backup SQL
                    </label>

                    <input type="file"
                        name="backup_file"
                        accept=".sql,.txt"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white"
                        required>

                    @error('backup_file')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button class="px-6 py-4 rounded-2xl bg-red-600 text-white font-extrabold hover:bg-red-700">
                    Restore Database
                </button>
            </form>
        </div>

        <div class="mt-6 rounded-3xl bg-amber-50 border border-amber-100 p-6 text-amber-800">
            <h3 class="font-extrabold mb-2">Catatan Penting</h3>
            <p class="text-sm leading-relaxed">
                File backup ini berisi data database. Simpan di tempat aman dan jangan dibagikan sembarangan.
                Untuk restore, file SQL bisa diimport melalui phpMyAdmin.
            </p>
        </div>
    </div>
</x-layouts.admin>
