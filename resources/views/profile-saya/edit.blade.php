@php
    $settings = \App\Models\SystemSetting::allSettings();

    $backUrl = match (auth()->user()->role) {
        'admin_rw' => route('admin.dashboard'),
        'ketua_rt' => route('rt.dashboard'),
        'warga' => route('warga.dashboard'),
        default => route('dashboard'),
    };

    $roleLabel = match (auth()->user()->role) {
        'admin_rw' => 'Admin RW',
        'ketua_rt' => 'Ketua RT',
        'warga' => 'Warga',
        default => 'User',
    };
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya - SiWarga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <h1 class="font-extrabold text-slate-900">Profile Saya</h1>
                    <p class="text-sm text-slate-500">
                        Kelola akun {{ $roleLabel }} di {{ $settings['rt_rw_name'] }}.
                    </p>
                </div>

                <a href="{{ $backUrl }}"
                   class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200">
                    Kembali
                </a>
            </div>
        </header>

        <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 px-5 py-4 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-50 border border-red-100 text-red-700 px-5 py-4">
                    <p class="font-bold mb-2">Ada data yang perlu diperbaiki:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Profile Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                        <div class="flex flex-col items-center text-center">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                     alt="{{ $user->name }}"
                                     class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100 shadow-sm">
                            @else
                                <div class="w-32 h-32 rounded-full bg-indigo-600 text-white flex items-center justify-center text-5xl font-extrabold border-4 border-indigo-100 shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <h2 class="text-xl font-extrabold text-slate-900 mt-5">
                                {{ $user->name }}
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $user->email }}
                            </p>

                            <span class="mt-4 inline-flex px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-xs font-extrabold">
                                {{ $roleLabel }}
                            </span>

                            @if ($user->role === 'ketua_rt')
                                <p class="text-sm text-slate-500 mt-4">
                                    RT {{ $user->rt->nomor_rt ?? '-' }}
                                </p>
                            @endif

                            @if ($user->role === 'warga')
                                <p class="text-sm text-slate-500 mt-4">
                                    {{ $user->warga->nama ?? 'Data warga belum terhubung' }}
                                </p>
                            @endif

                            @if ($user->profile_photo)
                                <form method="POST"
                                      action="{{ route('profile-saya.photo.remove') }}"
                                      class="mt-5"
                                      onsubmit="return confirm('Hapus foto profil?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="px-4 py-2 rounded-xl bg-red-50 text-red-600 text-sm font-bold hover:bg-red-100">
                                        Hapus Foto
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="lg:col-span-2">
                    <form method="POST"
                          action="{{ route('profile-saya.update') }}"
                          enctype="multipart/form-data"
                          class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">
                                Edit Informasi Akun
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Perbarui nama, email, password, dan foto profil akun Anda.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Nama
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Email
                                </label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Foto Profil
                            </label>

                            <input type="file"
                                   name="profile_photo"
                                   accept="image/png,image/jpeg,image/jpg,image/webp"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-indigo-700">

                            <p class="text-xs text-slate-500 mt-2">
                                Format: JPG, JPEG, PNG, WEBP. Maksimal 5MB.
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-6">
                            <h3 class="text-lg font-extrabold text-slate-900">
                                Ubah Password
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Kosongkan jika tidak ingin mengganti password.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Password Baru
                                </label>
                                <input type="password"
                                       name="password"
                                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Minimal 8 karakter">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Konfirmasi Password
                                </label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button type="submit"
                                    class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700">
                                Simpan Perubahan
                            </button>

                            <a href="{{ $backUrl }}"
                               class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
