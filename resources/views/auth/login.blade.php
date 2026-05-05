<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Inventaris Ibadah</title>

    <!-- Memanggil Tailwind CSS dari Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Memanggil Alpine.js untuk Animasi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-[#f3f8f9]">

<!-- State awal -->
<div x-data="{ isSignUp: {{ $errors->has('register') || $errors->has('name') ? 'true' : 'false' }} }"
     class="relative flex items-center justify-center min-h-screen overflow-hidden">

    <div class="absolute bottom-8 left-8 flex items-center gap-2 text-xs font-bold tracking-widest text-emerald-500 z-0">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> SISTEM AKTIF
    </div>
    <div class="absolute bottom-8 right-8 text-xs font-bold tracking-widest text-gray-400 z-0">
        V1.0.0 &bull; 2026
    </div>

    <!-- Container Utama -->
    <div class="relative w-full max-w-4xl h-[600px] bg-white rounded-3xl shadow-2xl overflow-hidden flex z-10">

        <!-- ============================== -->
        <!-- FORM LOGIN (Kiri)              -->
        <!-- BUMBU 1: scale-95 ke scale-100 & custom ease -->
        <!-- ============================== -->
        <div class="absolute top-0 left-0 w-1/2 h-full bg-white transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu z-10"
             :class="isSignUp ? 'translate-x-full opacity-0 scale-90 pointer-events-none' : 'translate-x-0 opacity-100 scale-100 pointer-events-auto'">
            <div class="flex flex-col items-center justify-center h-full px-12 text-center transition-transform duration-[800ms] delay-100 ease-[cubic-bezier(0.25,1,0.5,1)]"
                 :class="isSignUp ? '-translate-y-4' : 'translate-y-0'">

                <div class="flex gap-1 mb-4">
                    <span class="w-2 h-6 bg-cyan-400 rounded-full"></span>
                    <span class="w-2 h-8 bg-cyan-400 rounded-full"></span>
                    <span class="w-2 h-6 bg-cyan-400 rounded-full"></span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Inventaris Ibadah</h2>
                <p class="text-sm text-gray-500 mb-8">Sistem Manajemen Inventaris Tempat Ibadah</p>

                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf
                    <div class="mb-4 text-left">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all" placeholder="admin@example.com" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4 text-left">
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                        </div>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400 transition-all" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input id="remember_me" type="checkbox" class="w-4 h-4 text-cyan-500 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-cyan-500 font-semibold hover:underline">Lupa sandi?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-cyan-400 hover:bg-cyan-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-cyan-200 hover:-translate-y-1 hover:shadow-cyan-300">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================== -->
        <!-- FORM REGISTER (Kanan -> Kiri)  -->
        <!-- ============================== -->
        <div class="absolute top-0 left-0 w-1/2 h-full bg-white transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu z-0"
             :class="isSignUp ? 'translate-x-full opacity-100 scale-100 z-20 pointer-events-auto' : 'translate-x-full opacity-0 scale-90 pointer-events-none'">
            <div class="flex flex-col items-center justify-center h-full px-12 text-center transition-transform duration-[800ms] delay-100 ease-[cubic-bezier(0.25,1,0.5,1)]"
                 :class="isSignUp ? 'translate-y-0' : 'translate-y-4'">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Buat Akun</h2>

                <form method="POST" action="{{ route('register') }}" class="w-full">
                    @csrf
                    <div class="mb-3 text-left">
                        <input type="text" name="name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400" placeholder="Nama Lengkap" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div class="mb-3 text-left">
                        <input type="email" name="email" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400" placeholder="Email" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div class="mb-3 text-left">
                        <input type="password" name="password" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400" placeholder="Kata Sandi" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>
                    <div class="mb-6 text-left">
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-cyan-400 focus:border-cyan-400" placeholder="Konfirmasi Kata Sandi" required>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-cyan-400 hover:bg-cyan-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-cyan-200 hover:-translate-y-1 hover:shadow-cyan-300">
                        Daftar Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================== -->
        <!-- SLIDING OVERLAY PANEL          -->
        <!-- BUMBU 2: Custom bezier untuk efek "glide" -->
        <!-- ============================== -->
        <div class="absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-transform duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu z-30"
             :class="isSignUp ? '-translate-x-full' : 'translate-x-0'">

            <div class="bg-gradient-to-br from-cyan-400 to-teal-500 text-white relative left-[-100%] h-full w-[200%] transition-transform duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu"
                 :class="isSignUp ? 'translate-x-1/2' : 'translate-x-0'">

                <!-- Overlay Kiri -->
                <div class="absolute top-0 left-0 w-1/2 h-full flex flex-col items-center justify-center px-12 text-center transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu"
                     :class="isSignUp ? 'translate-x-0 opacity-100 scale-100' : '-translate-x-[20%] opacity-0 scale-95'">
                    <h2 class="text-3xl font-bold mb-4 text-white">Selamat Datang!</h2>
                    <p class="mb-8 text-cyan-50 leading-relaxed">Sudah punya akun? Silakan masuk untuk mengakses dashboard inventaris dan mulai mengelola.</p>
                    <!-- Efek Hover Tombol -->
                    <button @click="isSignUp = false" type="button" class="border-2 border-white text-white px-10 py-2.5 rounded-full font-bold transition-all duration-300 hover:bg-white hover:text-cyan-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                        Masuk
                    </button>
                </div>

                <!-- Overlay Kanan -->
                <div class="absolute top-0 right-0 w-1/2 h-full flex flex-col items-center justify-center px-12 text-center transition-all duration-[800ms] ease-[cubic-bezier(0.25,1,0.5,1)] transform-gpu"
                     :class="isSignUp ? 'translate-x-[20%] opacity-0 scale-95' : 'translate-x-0 opacity-100 scale-100'">
                    <h2 class="text-3xl font-bold mb-4 text-white">Halo, Kawan!</h2>
                    <p class="mb-8 text-cyan-50 leading-relaxed">Belum punya akun? Daftarkan diri Anda sekarang untuk mulai meminjam dan mendata barang.</p>
                    <!-- Efek Hover Tombol -->
                    <button @click="isSignUp = true" type="button" class="border-2 border-white text-white px-10 py-2.5 rounded-full font-bold transition-all duration-300 hover:bg-white hover:text-cyan-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                        Daftar
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>
</body>
</html>
