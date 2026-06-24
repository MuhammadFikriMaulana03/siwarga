<x-layouts.admin title="Edit Warga" header="Edit Warga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-4xl">
        <form action="{{ route('admin.wargas.update', $warga) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">RT</label>
                <select name="rt_id" id="rt_id" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih RT</option>
                    @foreach ($rts as $rt)
                        <option value="{{ $rt->id }}" @selected(old('rt_id', $warga->rt_id) == $rt->id)>
                            RT {{ $rt->nomor_rt }}
                        </option>
                    @endforeach
                </select>
                @error('rt_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Kartu Keluarga</label>
                <select name="kartu_keluarga_id"
                        id="kartu_keluarga_id"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tidak pilih KK</option>
                    @foreach ($kartuKeluargas as $kk)
                        <option value="{{ $kk->id }}"
                                data-rt-id="{{ $kk->rt_id }}"
                                data-no-kk="{{ $kk->no_kk }}"
                                data-alamat="{{ $kk->alamat }}"
                                @selected(old('kartu_keluarga_id', $warga->kartu_keluarga_id) == $kk->id)>
                            {{ $kk->no_kk }} - {{ $kk->kepala_keluarga }} - RT {{ $kk->rt->nomor_rt ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $warga->nik) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('nik')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">No KK</label>
                <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk', $warga->no_kk) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('no_kk')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $warga->nama) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('nama')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $warga->tempat_lahir) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('tempat_lahir')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $warga->tanggal_lahir) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('tanggal_lahir')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    <option value="L" @selected(old('jenis_kelamin', $warga->jenis_kelamin) == 'L')>
                        Laki-laki
                    </option>
                    <option value="P" @selected(old('jenis_kelamin', $warga->jenis_kelamin) == 'P')>
                        Perempuan
                    </option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Agama
                </label>

                @php
                    $agamaOptions = [
                        'Islam',
                        'Kristen Protestan',
                        'Katolik',
                        'Hindu',
                        'Buddha',
                        'Konghucu',
                        'Lainnya',
                    ];

                    $currentAgama = old('agama', in_array($warga->agama, $agamaOptions) ? $warga->agama : 'Lainnya');

                    $currentAgamaLainnya = old(
                        'agama_lainnya',
                        !in_array($warga->agama, $agamaOptions) ? $warga->agama : ''
                    );

                    $isAgamaLainnya = $currentAgama === 'Lainnya';
                @endphp

                <select name="agama"
                        id="agamaSelect"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Agama</option>

                    @foreach ($agamaOptions as $agama)
                        <option value="{{ $agama }}" @selected($currentAgama === $agama)>
                            {{ $agama }}
                        </option>
                    @endforeach
                </select>

                <div id="agamaLainnyaWrapper"
                    class="mt-3 {{ $isAgamaLainnya ? '' : 'hidden' }}">
                    <input type="text"
                        name="agama_lainnya"
                        id="agamaLainnyaInput"
                        value="{{ $currentAgamaLainnya }}"
                        placeholder="Tulis agama/kepercayaan lainnya"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <p class="text-xs text-slate-500 mt-1">
                        Isi bagian ini jika memilih agama lainnya.
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $warga->pekerjaan) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('pekerjaan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $warga->no_hp) }}"
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('no_hp')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Status Warga</label>
                <select name="status_warga" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="tetap" @selected(old('status_warga', $warga->status_warga) == 'tetap')>
                        Tetap
                    </option>
                    <option value="kontrak" @selected(old('status_warga', $warga->status_warga) == 'kontrak')>
                        Kontrak
                    </option>
                    <option value="pendatang" @selected(old('status_warga', $warga->status_warga) == 'pendatang')>
                        Pendatang
                    </option>
                </select>
                @error('status_warga')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Alamat</label>
                <textarea name="alamat" id="alamat" rows="4"
                          class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat', $warga->alamat) }}</textarea>
                @error('alamat')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1"
                           @checked(old('is_active', $warga->is_active))
                           class="rounded border-slate-300 text-indigo-600">
                    <span class="text-sm font-semibold">Warga Aktif</span>
                </label>
            </div>

            <div class="md:col-span-2 flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Update
                </button>

                <a href="{{ route('admin.wargas.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const kkSelect = document.getElementById('kartu_keluarga_id');
        const rtSelect = document.getElementById('rt_id');
        const noKkInput = document.getElementById('no_kk');
        const alamatTextarea = document.getElementById('alamat');

        if (!kkSelect) return;

        kkSelect.addEventListener('change', function () {
            const selectedOption = kkSelect.options[kkSelect.selectedIndex];

            const rtId = selectedOption.getAttribute('data-rt-id');
            const noKk = selectedOption.getAttribute('data-no-kk');
            const alamat = selectedOption.getAttribute('data-alamat');

            if (rtId && rtSelect) {
                rtSelect.value = rtId;
            }

            if (noKk && noKkInput) {
                noKkInput.value = noKk;
            }

            if (alamat && alamatTextarea) {
                alamatTextarea.value = alamat;
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const agamaSelect = document.getElementById('agamaSelect');
        const agamaLainnyaWrapper = document.getElementById('agamaLainnyaWrapper');
        const agamaLainnyaInput = document.getElementById('agamaLainnyaInput');

        if (!agamaSelect || !agamaLainnyaWrapper || !agamaLainnyaInput) {
            return;
        }

        function toggleAgamaLainnya() {
            if (agamaSelect.value === 'Lainnya') {
                agamaLainnyaWrapper.classList.remove('hidden');
                agamaLainnyaInput.setAttribute('required', 'required');
            } else {
                agamaLainnyaWrapper.classList.add('hidden');
                agamaLainnyaInput.removeAttribute('required');
                agamaLainnyaInput.value = '';
            }
        }

        agamaSelect.addEventListener('change', toggleAgamaLainnya);
        toggleAgamaLainnya();
    });
</script>
</x-layouts.admin>
