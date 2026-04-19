<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Inventaris Ibadah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-200 antialiased text-slate-800 min-h-screen flex flex-col justify-center items-center relative py-10 px-4">

    <div class="w-full max-w-[420px] bg-[#fdfdfd] rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-slate-300/50 border border-white z-10">
        
        <div class="w-16 h-16 bg-[#11d4d4]/10 rounded-full flex items-center justify-center mx-auto mb-5">
            <div class="flex gap-1.5 items-center">
                <div class="w-2.5 h-6 bg-[#11d4d4] rounded-full"></div>
                <div class="w-2.5 h-8 bg-[#11d4d4] rounded-full -translate-y-1"></div>
                <div class="w-2.5 h-6 bg-[#11d4d4] rounded-full"></div>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-1">Inventaris Ibadah</h1>
            <p class="text-[13px] text-slate-500 font-medium">Sistem Manajemen Inventaris Tempat Ibadah</p>
        </div>

        <x-auth-session-status class="mb-4 text-sm font-bold text-emerald-600 text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-xs font-black text-slate-700 mb-2">Email atau Username</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[20px] pointer-events-none">person</span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] transition-all outline-none placeholder:text-slate-400" 
                        placeholder="nama@domain.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-rose-500" />
            </div>

            <div class="mb-6">
    <div class="flex justify-between items-center mb-2">
        <label for="password" class="text-xs font-black text-slate-700">
            Kata Sandi
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-xs font-bold text-[#11d4d4] hover:text-[#0eb8b8] transition-colors">
                Lupa kata sandi?
            </a>
        @endif
    </div>

    <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[20px] pointer-events-none">
            lock
        </span>

        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] transition-all outline-none placeholder:text-slate-400"
            placeholder="••••••••"
        >

        <button
            type="button"
            id="togglePassword"
            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#11d4d4] transition-colors z-20"
        >
            <span id="eyeIcon" class="material-symbols-outlined text-[20px]">
                visibility_off
            </span>
        </button>
    </div>

    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-rose-500" />
</div>

            <div class="flex items-center mb-8">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#11d4d4] focus:ring-[#11d4d4] focus:ring-offset-0 bg-white" name="remember">
                <label for="remember_me" class="ml-2.5 text-sm font-medium text-slate-500 select-none cursor-pointer">Ingat Saya</label>
            </div>

            <button type="submit" class="w-full bg-[#11d4d4] text-white font-black text-sm py-3.5 rounded-2xl shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] hover:shadow-xl hover:shadow-[#11d4d4]/40 active:scale-[0.98] transition-all flex justify-center items-center gap-2">
                Masuk <span class="material-symbols-outlined text-[20px]">login</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <p class="text-sm font-medium text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-[#11d4d4] font-bold hover:text-[#0eb8b8] transition-colors ml-1">Daftar di sini</a>
            </p>
        </div>
    </div>

    <div class="w-full max-w-[420px] flex justify-between items-center mt-6 text-slate-400 px-2">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-sm shadow-emerald-400/50"></span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Sistem Aktif</span>
        </div>
        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">v1.0.0 • © {{ date('Y') }}</span>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {

    const passwordInput = document.getElementById("password");
    const toggleBtn = document.getElementById("togglePassword");
    const eyeIcon = document.getElementById("eyeIcon");

    toggleBtn.addEventListener("click", function () {

        const isPassword = passwordInput.type === "password";

        passwordInput.type = isPassword ? "text" : "password";

        eyeIcon.textContent = isPassword
            ? "visibility"
            : "visibility_off";
    });

});
</script>

</body>
</html>