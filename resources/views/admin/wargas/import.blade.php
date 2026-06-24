<x-layouts.admin title="Import Data Warga" header="Import Data Warga">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
        <div class="mb-6">
            <h3 class="text-lg font-bold">Import Data Warga dari CSV</h3>
            <p class="text-sm text-slate-500 mt-1">
                Upload file CSV hasil export atau format yang sama dari Excel.
            </p>
        </div>

        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6 p-5 rounded-2xl bg-slate-50 border border-slate-100">
            <p class="font-bold mb-2">Format kolom CSV:</p>
            <p class="text-sm text-slate-600 leading-relaxed">
                No, NIK, No KK, Nama, RT, Jenis Kelamin, Tempat Lahir, Tanggal Lahir,
                Alamat, Agama, Pekerjaan, No HP, Status Warga, Status Aktif
            </p>

            <p class="text-sm text-slate-500 mt-3">
                Tips: lebih aman pakai tombol Export CSV dulu, lalu edit file tersebut dan upload kembali.
            </p>
        </div>

        <form action="{{ route('admin.wargas.import') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">File CSV</label>
                <input type="file"
                       name="file"
                       accept=".csv,.txt"
                       class="w-full rounded-xl border border-slate-300 p-3">
                @error('file')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Import Data
                </button>

                <a href="{{ route('admin.wargas.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
