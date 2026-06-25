<x-layouts.admin title="Inventaris RW" header="Inventaris RW">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold">
                    Inventaris RW
                </h3>

                <p class="text-sm text-slate-500">
                    Kelola seluruh aset dan barang inventaris RW.
                </p>
            </div>

            <a href="{{ route('admin.inventaris.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                + Tambah Barang
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b bg-slate-50 text-left">
                        <th class="p-4">Kode</th>
                        <th class="p-4">Nama Barang</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Jumlah</th>
                        <th class="p-4">Kondisi</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($inventaris as $item)

                        <tr class="border-b">

                            <td class="p-4 font-mono">
                                {{ $item->kode_barang }}
                            </td>

                            <td class="p-4 font-semibold">
                                {{ $item->nama_barang }}
                            </td>

                            <td class="p-4">
                                {{ $item->kategori }}
                            </td>

                            <td class="p-4">
                                {{ $item->jumlah }}
                            </td>

                            <td class="p-4">

                                @php
                                    $badge = match($item->kondisi){
                                        'Baik' => 'bg-emerald-50 text-emerald-700',
                                        'Rusak Ringan' => 'bg-amber-50 text-amber-700',
                                        'Rusak Berat' => 'bg-red-50 text-red-700',
                                        default => 'bg-slate-100 text-slate-700'
                                    };
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ $item->kondisi }}
                                </span>

                            </td>

                            <td class="p-4">
                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.inventaris.edit', $item) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.inventaris.destroy', $item) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus barang ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="px-3 py-2 rounded-lg bg-red-50 text-red-700 font-semibold">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="p-6 text-center text-slate-500">
                                Belum ada data inventaris.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</x-layouts.admin>
