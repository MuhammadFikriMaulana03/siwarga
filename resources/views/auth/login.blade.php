<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiWarga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
    @php
    $settings = \App\Models\SystemSetting::allSettings();
    @endphp

    {{-- Background Effects --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Login Card --}}
    <div class="relative w-full max-w-md mx-4">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8 sm:p-10">
            {{-- Logo & Title --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-700 text-white shadow-lg shadow-indigo-500/30 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">SiWarga</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $settings['rt_rw_name'] }}</p>
            </div>

            {{-- Header --}}
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900">Masuk</h2>
                <p class="text-sm text-slate-500 mt-1">Gunakan akun yang telah dibuat oleh Admin</p>
            </div>

            {{-- Alerts --}}
            @if (session('status'))
                <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm">
                    <p class="font-bold mb-1">Login gagal</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="nama@email.com"
                               class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-12 pr-4 py-3.5 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-12 pr-12 py-3.5 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition">
                        <button type="button"
                                id="togglePasswordBtn"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <svg class="w-5 h-5 text-slate-400 hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg class="w-5 h-5 text-slate-400 hover:text-indigo-600 transition hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeOffIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me"
                               type="checkbox"
                               name="remember"
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-3.5 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 hover:from-indigo-700 hover:to-indigo-800 active:scale-[0.98] transition-all duration-200">
                    Masuk
                </button>
            </form>

            {{-- Footer --}}
            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Belum punya akun?
                    <span class="font-medium text-slate-700">Hubungi Admin RW</span>
                </p>
            </div>

            {{-- Back to Home --}}
            <div class="mt-4 text-center">
                <a href="{{ route('landing') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-indigo-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        {{-- Footer Credits --}}
        <p class="text-center text-xs text-slate-500/60 mt-6">
            &copy; {{ date('Y') }} SiWarga - {{ $settings['rt_rw_name'] }}
        </p>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        togglePasswordBtn.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
