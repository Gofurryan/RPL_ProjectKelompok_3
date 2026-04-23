<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-[2rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#11d4d4]/10 rounded-bl-full -z-0"></div>

        <div class="relative z-10">
            <div class="w-16 h-16 bg-[#11d4d4]/10 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-[#11d4d4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4v-3.252l.71-.71a3.38 3.38 0 00.92-1.636L6.5 13.5l3.86-3.86a6 6 0 018.64-2.64z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Atur Ulang Sandi</h1>
            <p class="text-sm text-slate-500 font-medium leading-relaxed mb-8">
                Silakan masukkan kata sandi baru Anda untuk memulihkan akses akun.
            </p>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required readonly
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-500 outline-none">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Kata Sandi Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" type="password" required autofocus
                            class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
                        
                        <button type="button" onclick="toggleVisibility('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-[#11d4d4]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path class="slash hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-rose-500" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Konfirmasi Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
                        
                        <button type="button" onclick="toggleVisibility('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-[#11d4d4]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path class="slash hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-[#11d4d4] text-white font-bold text-sm py-4 rounded-xl shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] active:scale-[0.98] transition-all">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const slash = btn.querySelector('.slash');
            
            if (input.type === 'password') {
                input.type = 'text';
                slash.classList.remove('hidden');
            } else {
                input.type = 'password';
                slash.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>