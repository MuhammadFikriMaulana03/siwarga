<x-layouts.admin>
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Log Aktivitas</h1>
                <p class="text-slate-500 text-sm">
                    Riwayat aktivitas penting yang dilakukan user di sistem.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.activity-logs.export', request()->query()) }}"
                    class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700">
                    Export CSV
                </a>

                <a href="{{ route('admin.activity-logs.pdf', request()->query()) }}"
                    target="_blank"
                    class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700">
                    Export PDF
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold">Daftar Aktivitas</h2>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-5">
                    @method('PUT')
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari user, aksi, keterangan..."
                           class="md:col-span-2 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <select name="module"
                            class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Modul</option>
                        @foreach ($modules as $item)
                            <option value="{{ $item }}" {{ $module === $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2 md:col-span-2">
                        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                            Filter
                        </button>

                        <a href="{{ route('admin.activity-logs.index') }}"
                           class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-left border-b">
                            <th class="p-4">Waktu</th>
                            <th class="p-4">User</th>
                            <th class="p-4">Modul</th>
                            <th class="p-4">Aksi</th>
                            <th class="p-4">Keterangan</th>
                            <th class="p-4">IP</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-4">
                                    <p class="font-bold">
                                        {{ $log->created_at->translatedFormat('d M Y') }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </p>
                                </td>

                                <td class="p-4">
                                    <p class="font-bold">
                                        {{ $log->user->name ?? 'System' }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $log->role ?? '-' }}
                                    </p>
                                </td>

                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">
                                        {{ $log->module }}
                                    </span>
                                </td>

                                <td class="p-4 font-bold">
                                    {{ $log->action }}
                                </td>

                                <td class="p-4 text-slate-600">
                                    {{ $log->description ?? '-' }}
                                </td>

                                <td class="p-4 text-slate-500">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    Belum ada log aktivitas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
