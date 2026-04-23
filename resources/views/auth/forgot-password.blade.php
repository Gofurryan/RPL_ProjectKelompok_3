<x-guest-layout>
    s
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#11d4d4]/10 rounded-bl-full -z-0"></div>

        <div class="relative z-10">
            <div class="w-16 h-16 bg-[#11d4d4]/10 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-[#11d4d4]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Lupa Kata Sandi?</h1>
            
            <p class="text-sm text-slate-500 font-medium leading-relaxed mb-8">
                Jangan khawatir! Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
            </p>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs font-bold text-emerald-700 leading-snug">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com"
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] focus:bg-white outline-none transition-all">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-rose-500" />
                </div>

                <div class="flex flex-col gap-4 pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-[#11d4d4] text-white font-bold text-sm py-4 rounded-xl shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] active:scale-[0.98] transition-all">
                        Kirim Tautan Pemulihan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>

                    <a href="{{ route('login') }}" class="flex justify-center items-center gap-2 py-2 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>