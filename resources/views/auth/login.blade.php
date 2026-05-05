<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ request()->routeIs('register') ? 'Daftar' : 'Masuk' }} - Inventaris Ibadah</title>

    <!-- Memanggil Tailwind CSS dari Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Ikon Material Google (Untuk ikon mata / show hide password) -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

    <!-- Memanggil Alpine.js untuk Animasi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Mencegah Alpine berkedip saat pertama kali diload */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-[#f3f8f9]">

<!-- State Utama Slider -->
<div x-data="{ isSignUp: {{ request()->routeIs('register') || old('name') || old('phone') || old('role') ? 'true' : 'false' }} }"
     class="relative flex items-center justify-center min-h-screen overflow-hidden">

    <div class="absolute bottom-8 left-8 flex items-center gap-2 text-xs font-bold tracking-widest text-emerald-500 z-0">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> SISTEM AKTIF
    </div>
    <div class="absolute bottom-8 right-8 text-xs font-bold tracking-widest text-gray-400 z-0">
        V1.0.0 &bull; 2026
    </div>

    <div class="relative w-full max-w-4xl h-[700px] bg-white rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] overflow-hidden flex z-10">

        <!-- ============================== -->
        <!-- FORM LOGIN (Sisi Kiri)         -->
        <!-- ============================== -->
        <div class="absolute top-0 left-0 w-1/2 h-full bg-white transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu flex flex-col justify-center px-12 text-center"
             :class="isSignUp ? 'translate-x-[20%] opacity-0 scale-95 pointer-events-none z-0' : 'translate-x-0 opacity-100 scale-100 pointer-events-auto z-10'">

            <div class="transition-transform duration-[800ms] delay-75 ease-[cubic-bezier(0.25,1,0.5,1)]" :class="isSignUp ? 'translate-y-6' : 'translate-y-0'">
                <div class="flex gap-1 mb-4 justify-center">
                    <span class="w-2 h-6 bg-cyan-400 rounded-full"></span>
                    <span class="w-2 h-8 bg-cyan-400 rounded-full"></span>
                    <span class="w-2 h-6 bg-cyan-400 rounded-full"></span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Inventaris Ibadah</h2>
                <p class="text-sm text-gray-500 mb-8">Sistem Manajemen Inventaris Tempat Ibadah</p>

                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf
                    <div class="mb-4 text-left">
                        <label for="login_email" class="block text-sm font-semibold text-gray-700 mb-1">Email atau Username</label>
                        <input id="login_email" type="text" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white" placeholder="admin@example.com" required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- FITUR MATA (LOGIN) -->
                    <div class="mb-4 text-left" x-data="{ show: false }">
                        <div class="flex justify-between items-center mb-1">
                            <label for="login_password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                        </div>
                        <div class="relative">
                            <!-- :type akan berubah jadi text kalau 'show' bernilai true -->
                            <input id="login_password" :type="show ? 'text' : 'password'" name="password" class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white" required autocomplete="current-password">

                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-cyan-500 focus:outline-none transition-colors z-20">
                                <span class="material-symbols-outlined text-[22px]" x-text="show ? 'visibility' : 'visibility_off'">visibility_off</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="w-4 h-4 text-cyan-500 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-cyan-500 font-semibold hover:underline">Lupa sandi?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-cyan-400 hover:bg-cyan-500 text-white font-bold rounded-xl transition-all shadow-[0_8px_20px_-6px_rgba(34,211,238,0.5)] hover:-translate-y-1 hover:shadow-[0_12px_25px_-6px_rgba(34,211,238,0.6)]">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================== -->
        <!-- FORM REGISTER (Sisi Kanan)     -->
        <!-- ============================== -->
        <div class="absolute top-0 left-1/2 w-1/2 h-full bg-white transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu flex flex-col justify-center px-10 text-center"
             :class="isSignUp ? 'translate-x-0 opacity-100 scale-100 pointer-events-auto z-10' : '-translate-x-[20%] opacity-0 scale-95 pointer-events-none z-0'">

            <div class="transition-transform duration-[800ms] delay-75 ease-[cubic-bezier(0.25,1,0.5,1)]" :class="isSignUp ? 'translate-y-0' : 'translate-y-6'">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Buat Akun</h2>

                <form method="POST" action="{{ route('register') }}" class="w-full">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="mb-3 text-left">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white placeholder:text-slate-400 text-sm" required autofocus autocomplete="name" placeholder="Nama Lengkap">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Email -->
                    <div class="mb-3 text-left">
                        <input id="email_register" type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white placeholder:text-slate-400 text-sm" required autocomplete="username" placeholder="Alamat Email">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Role -->
                    <div class="mb-3 text-left">
                        <select id="role" name="role" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white text-gray-700 text-sm" required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Peran / Role</option>
                            <option value="warga" {{ old('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="mb-3 text-left">
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white placeholder:text-slate-400 text-sm" required autocomplete="tel" placeholder="Nomor WhatsApp">
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <!-- FITUR MATA (REGISTER) - Password & Confirm -->
                    <div class="mb-6 text-left flex gap-2">

                        <!-- Input Kata Sandi -->
                        <div class="w-1/2" x-data="{ show: false }">
                            <div class="relative">
                                <input id="password_register" :type="show ? 'text' : 'password'" name="password" class="w-full px-4 py-2 pr-10 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white placeholder:text-slate-400 text-sm" required autocomplete="new-password" placeholder="Kata Sandi">
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-cyan-500 focus:outline-none transition-colors z-20">
                                    <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility' : 'visibility_off'">visibility_off</span>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                        </div>

                        <!-- Input Konfirmasi -->
                        <div class="w-1/2" x-data="{ show: false }">
                            <div class="relative">
                                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" class="w-full px-4 py-2 pr-10 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all focus:bg-white placeholder:text-slate-400 text-sm" required autocomplete="new-password" placeholder="Konfirmasi">
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-cyan-500 focus:outline-none transition-colors z-20">
                                    <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility' : 'visibility_off'">visibility_off</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-cyan-400 hover:bg-cyan-500 text-white font-bold rounded-xl transition-all shadow-[0_8px_20px_-6px_rgba(34,211,238,0.5)] hover:-translate-y-1 hover:shadow-[0_12px_25px_-6px_rgba(34,211,238,0.6)]">
                        Daftar Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================== -->
        <!-- SLIDING OVERLAY PANEL          -->
        <!-- ============================== -->
        <div class="absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-transform duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu z-30 shadow-[-10px_0_30px_-10px_rgba(0,0,0,0.1)]"
             :class="isSignUp ? '-translate-x-full shadow-[10px_0_30px_-10px_rgba(0,0,0,0.1)]' : 'translate-x-0'">

            <div class="bg-gradient-to-br from-cyan-400 to-teal-500 text-white relative left-[-100%] h-full w-[200%] transition-transform duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu"
                 :class="isSignUp ? 'translate-x-1/2' : 'translate-x-0'">

                <div class="absolute top-0 left-0 w-1/2 h-full flex flex-col items-center justify-center px-14 text-center transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu"
                     :class="isSignUp ? 'translate-x-0 opacity-100 scale-100' : '-translate-x-[20%] opacity-0 scale-95'">
                    <h2 class="text-3xl font-extrabold mb-4 text-white drop-shadow-md">Selamat Datang!</h2>
                    <p class="mb-8 text-cyan-50 leading-relaxed font-medium">Sudah punya akun? Silakan masuk untuk mengakses dashboard inventaris dan mulai mengelola.</p>
                    <a href="{{ route('login') }}" @click.prevent="isSignUp = false; window.history.pushState({}, '', '{{ route('login') }}')"
                       class="border-2 border-white text-white px-12 py-3 rounded-full font-bold transition-all duration-300 hover:bg-white hover:text-cyan-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                        Masuk
                    </a>
                </div>

                <div class="absolute top-0 right-0 w-1/2 h-full flex flex-col items-center justify-center px-14 text-center transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu"
                     :class="isSignUp ? 'translate-x-[20%] opacity-0 scale-95' : 'translate-x-0 opacity-100 scale-100'">
                    <h2 class="text-3xl font-extrabold mb-4 text-white drop-shadow-md">Halo, Kawan!</h2>
                    <p class="mb-8 text-cyan-50 leading-relaxed font-medium">Belum punya akun? Daftarkan diri Anda sekarang untuk mulai meminjam dan mendata barang.</p>
                    <a href="{{ route('register') }}" @click.prevent="isSignUp = true; window.history.pushState({}, '', '{{ route('register') }}')"
                       class="border-2 border-white text-white px-12 py-3 rounded-full font-bold transition-all duration-300 hover:bg-white hover:text-cyan-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                        Daftar
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
</body>
</html>
