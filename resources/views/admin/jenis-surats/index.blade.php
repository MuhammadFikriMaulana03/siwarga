<x-layouts.admin>
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Jenis Surat</h1>
                <p class="text-slate-500 text-sm">Kelola jenis layanan surat warga.</p>
            </div>

            <a href="{{ route('admin.jenis-surats.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                + Tambah Jenis Surat
            </a>
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

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold">Daftar Jenis Surat</h2>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-5">
                    @method('PUT')
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari jenis surat..."
                           class="md:col-span-2 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <select name="status"
                            class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>

                    <div class="flex gap-2 md:col-span-2">
                        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                            Filter
                        </button>

                        <a href="{{ route('admin.jenis-surats.index') }}"
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
                            <th class="p-4">No</th>
                            <th class="p-4">Nama</th>
                            <th class="p-4">Deskripsi</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($jenisSurats as $jenisSurat)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-4">
                                    {{ $jenisSurats->firstItem() + $loop->index }}
                                </td>

                                <td class="p-4 font-bold">
                                    {{ $jenisSurat->nama }}
                                </td>

                                <td class="p-4 text-slate-600">
                                    {{ $jenisSurat->deskripsi ?? '-' }}
                                </td>

                                <td class="p-4">
                                    @if ($jenisSurat->is_active)
                                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.jenis-surats.edit', $jenisSurat) }}"
                                           class="px-3 py-2 rounded-xl bg-amber-50 text-amber-700 text-xs font-bold hover:bg-amber-100">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.jenis-surats.destroy', $jenisSurat) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus jenis surat ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="px-3 py-2 rounded-xl bg-red-50 text-red-700 text-xs font-bold hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">
                                    Belum ada jenis surat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $jenisSurats->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
