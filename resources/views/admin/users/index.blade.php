<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col gap-6">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Data Warga</h1>
                <p class="text-sm text-slate-500 font-medium">Daftar warga terdaftar dan informasi kontak WhatsApp.</p>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
                <div class="relative">
                    <span
                        class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[20px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari warga..."
                        class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] outline-none w-full md:w-64 transition-all">
                </div>
                <button type="submit"
                    class="bg-[#11d4d4] text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-[#0eb8b8] transition-all">
                    Cari
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th
                                class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center w-16">
                                No</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Warga
                            </th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Kontak
                            </th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Status
                            </th>
                            <th
                                class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-5 text-center text-sm font-bold text-slate-400">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=11d4d4&color=fff' }}"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800">{{ $user->name }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700">{{ $user->phone ?? '-' }}</span>

                                        @if($user->phone)
                                            <span class="text-[10px] font-black text-[#11d4d4] uppercase tracking-tighter">Nomor
                                                Tersedia</span>
                                        @else
                                            <span class="text-[10px] font-black text-rose-500 uppercase tracking-tighter">Belum
                                                Diisi</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    @if($user->email_verified_at)
                                        <span
                                            class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-200">
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full bg-amber-100 text-amber-600 text-[10px] font-black uppercase tracking-widest border border-amber-200">
                                            Belum Verifikasi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($user->phone)
                                        @php
                                            // Bersihkan nomor dari karakter non-angka
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                                            // Ubah 08 ke 62
                                            if (str_starts_with($cleanPhone, '0')) {
                                                $cleanPhone = '62' . substr($cleanPhone, 1);
                                            }
                                        @endphp
                                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#25D366] text-white rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-[#20ba5a] transition-all shadow-lg shadow-emerald-200/50">
                                            <span class="material-symbols-outlined text-[16px]">chat</span> Hubungi
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-300 italic">No WhatsApp Kosong</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="material-symbols-outlined text-4xl text-slate-200">group_off</span>
                                        <p class="text-sm text-slate-400 font-medium">Belum ada warga yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>