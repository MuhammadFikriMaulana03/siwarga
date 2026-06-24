<x-layouts.admin>
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Kelola User</h1>
                <p class="text-slate-500 text-sm">
                    Kelola akun login admin, ketua RT, dan warga. Register publik dinonaktifkan, akun dibuat melalui halaman ini.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                + Tambah User
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
                <h2 class="text-lg font-bold">Daftar User</h2>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-5">
                    @method('PUT')
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nama, email, warga..."
                           class="md:col-span-2 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <select name="role"
                            class="rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Role</option>
                        <option value="admin_rw" {{ $role === 'admin_rw' ? 'selected' : '' }}>Admin RW</option>
                        <option value="ketua_rt" {{ $role === 'ketua_rt' ? 'selected' : '' }}>Ketua RT</option>
                        <option value="warga" {{ $role === 'warga' ? 'selected' : '' }}>Warga</option>
                    </select>

                    <div class="flex gap-2 md:col-span-2">
                        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                            Filter
                        </button>

                        <a href="{{ route('admin.users.index') }}"
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
                            <th class="p-4">User</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">RT</th>
                            <th class="p-4">Warga Terhubung</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-4">{{ $users->firstItem() + $loop->index }}</td>

                                <td class="p-4">
                                    <p class="font-bold">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </td>

                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        {{ $user->role === 'admin_rw' ? 'bg-indigo-50 text-indigo-700' : '' }}
                                        {{ $user->role === 'ketua_rt' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                        {{ $user->role === 'warga' ? 'bg-amber-50 text-amber-700' : '' }}">
                                        {{ str_replace('_', ' ', strtoupper($user->role)) }}
                                    </span>
                                </td>

                                <td class="p-4">
                                    {{ $user->rt ? 'RT ' . $user->rt->nomor_rt : '-' }}
                                </td>

                                <td class="p-4">
                                    @if ($user->warga)
                                        <p class="font-semibold">{{ $user->warga->nama }}</p>
                                        <p class="text-xs text-slate-500">NIK {{ $user->warga->nik }}</p>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="px-3 py-2 rounded-xl bg-amber-50 text-amber-700 text-xs font-bold hover:bg-amber-100">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus user ini?')">
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
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    Belum ada user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
