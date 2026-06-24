<x-layouts.admin>
    <div class="p-6 max-w-4xl">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-slate-900">Pengaturan Sistem</h1>
            <p class="text-slate-500 text-sm">
                Kelola identitas RT/RW, alamat sekretariat, dan kop surat.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-2xl bg-emerald-50 text-emerald-700 px-5 py-4 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama RT/RW
                    </label>
                    <input type="text"
                           name="rt_rw_name"
                           value="{{ old('rt_rw_name', $settings['rt_rw_name']) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Contoh: RW 11"
                           required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kelurahan
                        </label>
                        <input type="text"
                               name="kelurahan"
                               value="{{ old('kelurahan', $settings['kelurahan']) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kecamatan
                        </label>
                        <input type="text"
                               name="kecamatan"
                               value="{{ old('kecamatan', $settings['kecamatan']) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kota/Kabupaten
                        </label>
                        <input type="text"
                               name="kota"
                               value="{{ old('kota', $settings['kota']) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Alamat Sekretariat
                    </label>
                    <textarea name="alamat_sekretariat"
                              rows="3"
                              class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                              required>{{ old('alamat_sekretariat', $settings['alamat_sekretariat']) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            No HP RW
                        </label>
                        <input type="text"
                               name="no_hp_rw"
                               value="{{ old('no_hp_rw', $settings['no_hp_rw']) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Ketua RW
                        </label>
                        <input type="text"
                               name="ketua_rw"
                               value="{{ old('ketua_rw', $settings['ketua_rw']) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>
                </div>

                <button class="w-full px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                    Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>
