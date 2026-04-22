<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col gap-8">
        
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Pengaturan Akun</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola informasi profil dan keamanan akun Anda di sini.</p>
        </div>

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl shadow-sm border border-emerald-100 flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="text-sm font-bold">Perubahan berhasil disimpan!</p>
            </div>
        @endif

        <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#11d4d4]/5 rounded-bl-full -z-10"></div>

            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-12 h-12 rounded-full bg-[#11d4d4]/10 text-[#11d4d4] flex items-center justify-center">
                    <span class="material-symbols-outlined">manage_accounts</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Informasi Profil</h2>
                    <p class="text-xs text-slate-500">Perbarui nama, email, dan nomor WhatsApp.</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col gap-6 max-w-xl">
    @csrf
    @method('patch')

    <div class="flex items-center gap-6 mb-2">
        <div class="shrink-0">
            @php
                $avatarUrl = $user->avatar 
                    ? asset('storage/avatars/' . $user->avatar) 
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=11d4d4&color=fff';
            @endphp
            <img id="preview-avatar" src="{{ $avatarUrl }}" class="h-24 w-24 object-cover rounded-3xl border-4 border-[#11d4d4]/10 shadow-md">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-black text-slate-700 mb-3">Foto Profil</label>
            <input type="file" name="avatar" onchange="previewImage(event)"
                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#11d4d4]/10 file:text-[#11d4d4] hover:file:bg-[#11d4d4]/20 cursor-pointer transition-colors">
            <p class="mt-2 text-xs text-slate-400 font-medium">JPG, PNG atau JPEG. Maksimal 2MB.</p>
            <x-input-error class="mt-2 text-xs font-bold text-rose-500" :messages="$errors->get('avatar')" />
        </div>
    </div>

    <div>
        <label for="name" class="block text-sm font-black text-slate-700 mb-2">Nama Lengkap</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[22px] pointer-events-none">person</span>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
        </div>
        <x-input-error class="mt-2 text-xs font-bold text-rose-500" :messages="$errors->get('name')" />
    </div>

    <div>
        <label for="email" class="block text-sm font-black text-slate-700 mb-2">Alamat Email</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[22px] pointer-events-none">mail</span>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
        </div>
        <x-input-error class="mt-2 text-xs font-bold text-rose-500" :messages="$errors->get('email')" />
    </div>

    <div>
        <label for="phone" class="block text-sm font-black text-slate-700 mb-2">Nomor WhatsApp</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[22px] pointer-events-none">call</span>
            <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone ?? '') }}" required
                class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
        </div>
        <x-input-error class="mt-2 text-xs font-bold text-rose-500" :messages="$errors->get('phone')" />
    </div>

    <div class="pt-4 border-t border-slate-100 mt-2">
        <button type="submit" class="bg-[#11d4d4] text-white font-black text-sm px-8 py-3.5 rounded-2xl shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] active:scale-[0.98] transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
            Simpan Profil <span class="material-symbols-outlined text-[20px]">save</span>
        </button>
    </div>
</form>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview-avatar');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden">
             <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/5 rounded-bl-full -z-10"></div>

            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center">
                    <span class="material-symbols-outlined">lock_reset</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Ubah Kata Sandi</h2>
                    <p class="text-xs text-slate-500">Pastikan akun Anda menggunakan kata sandi acak yang panjang.</p>
                </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-5 max-w-xl">
                @csrf
                @method('put')

                <div x-data="{ show: false }">
                    <label for="update_password_current_password" class="block text-xs font-black text-slate-700 mb-2">Kata Sandi Saat Ini</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[20px] pointer-events-none">lock</span>
                        <input id="update_password_current_password" name="current_password" :type="show ? 'text' : 'password'" autocomplete="current-password"
                            class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
                        
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#11d4d4] z-20">
                            <span class="material-symbols-outlined text-[20px]" x-show="!show">visibility_off</span>
                            <span class="material-symbols-outlined text-[20px]" x-show="show" x-cloak>visibility</span>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs font-bold text-rose-500" />
                </div>

                <div x-data="{ show: false }">
                    <label for="update_password_password" class="block text-xs font-black text-slate-700 mb-2">Kata Sandi Baru</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[20px] pointer-events-none">key</span>
                        <input id="update_password_password" name="password" :type="show ? 'text' : 'password'" autocomplete="new-password"
                            class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
                        
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#11d4d4] z-20">
                            <span class="material-symbols-outlined text-[20px]" x-show="!show">visibility_off</span>
                            <span class="material-symbols-outlined text-[20px]" x-show="show" x-cloak>visibility</span>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs font-bold text-rose-500" />
                </div>

                <div x-data="{ show: false }">
                    <label for="update_password_password_confirmation" class="block text-xs font-black text-slate-700 mb-2">Konfirmasi Sandi Baru</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[20px] pointer-events-none">lock_reset</span>
                        <input id="update_password_password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" autocomplete="new-password"
                            class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none transition-all">
                        
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#11d4d4] z-20">
                            <span class="material-symbols-outlined text-[20px]" x-show="!show">visibility_off</span>
                            <span class="material-symbols-outlined text-[20px]" x-show="show" x-cloak>visibility</span>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs font-bold text-rose-500" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-slate-800 text-white font-black text-sm px-6 py-3 rounded-2xl shadow-lg hover:bg-slate-700 active:scale-[0.98] transition-all flex items-center gap-2">
                        Perbarui Sandi <span class="material-symbols-outlined text-[18px]">key</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>