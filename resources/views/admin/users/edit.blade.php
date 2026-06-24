<x-layouts.admin>
    <div class="p-6 max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex mb-4 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                Kembali
            </a>

            <h1 class="text-2xl font-extrabold text-slate-900">Edit User</h1>
            <p class="text-slate-500 text-sm">Ubah data akun login.</p>
        </div>

        @if (session('error'))
            <div class="mb-5 rounded-2xl bg-red-50 text-red-700 px-5 py-4 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Baru <span class="text-slate-400">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Role</label>
                    <select name="role" id="roleSelect"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Pilih Role</option>
                        <option value="admin_rw" {{ old('role', $user->role) === 'admin_rw' ? 'selected' : '' }}>Admin RW</option>
                        <option value="ketua_rt" {{ old('role', $user->role) === 'ketua_rt' ? 'selected' : '' }}>Ketua RT</option>
                        <option value="warga" {{ old('role', $user->role) === 'warga' ? 'selected' : '' }}>Warga</option>
                    </select>
                </div>

                <div id="rtField" class="hidden">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Hubungkan ke RT</label>
                    <select name="rt_id"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih RT</option>
                        @foreach ($rts as $rt)
                            <option value="{{ $rt->id }}" {{ old('rt_id', $user->rt_id) == $rt->id ? 'selected' : '' }}>
                                RT {{ $rt->nomor_rt }} - {{ $rt->nama_ketua_rt ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="wargaField" class="hidden">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Hubungkan ke Warga</label>
                    <select name="warga_id"
                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Warga</option>
                        @foreach ($wargas as $warga)
                            <option value="{{ $warga->id }}" {{ old('warga_id', $user->warga_id) == $warga->id ? 'selected' : '' }}>
                                {{ $warga->nama }} - NIK {{ $warga->nik }} - RT {{ $warga->rt->nomor_rt ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="w-full px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const rtField = document.getElementById('rtField');
        const wargaField = document.getElementById('wargaField');

        function toggleFields() {
            rtField.classList.add('hidden');
            wargaField.classList.add('hidden');

            if (roleSelect.value === 'ketua_rt') {
                rtField.classList.remove('hidden');
            }

            if (roleSelect.value === 'warga') {
                wargaField.classList.remove('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    </script>
</x-layouts.admin>
