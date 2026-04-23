<aside class="w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col h-full">
    <div class="p-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-white">
            <span class="material-symbols-outlined text-2xl">mosque</span>
        </div>
        <div>
            <h1 class="text-sm font-bold leading-tight">Sistem Inventaris</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tempat Ibadah</p>
        </div>
    </div>
    
        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('*dashboard*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition-colors">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role == 'petugas')
                <a href="{{ route('items.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('items.*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition-colors">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <span>Data Barang</span>
            </a>
            <a href="{{ route('admin.loans.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.loans.*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition-colors">
                <span class="material-symbols-outlined">handshake</span>
                <span>Manajemen Pinjam</span>
            </a>
            <a href="{{ route('admin.reports.penalties') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition-colors">
                <span class="material-symbols-outlined">description</span>
                <span>Laporan Denda</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition-colors">
                <span class="material-symbols-outlined">group</span>
                <span class=>Data Warga</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 px-4 py-3 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-[#11d4d4]/10 text-[#11d4d4] font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }} transition-colors">
                <span class="material-symbols-outlined">settings</span>
                <span class=>Pengaturan</span>
            </a>
        @endif

        @if(strtolower(auth()->user()->role) == 'warga')
    <a href="{{ route('loans.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('loans.create') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition-colors">
        <span class="material-symbols-outlined">add_circle</span>
        <span>Booking Barang</span>
    </a>

    <a href="{{ route('warga.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('warga.history') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} transition-colors">
        <span class="material-symbols-outlined">history</span>
        <span>Riwayat Peminjaman</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 px-4 py-3 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-[#11d4d4]/10 text-[#11d4d4] font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }} transition-colors">
                <span class="material-symbols-outlined">settings</span>
                <span class=>Pengaturan</span>
    </a>
@endif
    </nav>

    <div class="p-4 border-t border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3 p-4 mt-auto border-t border-slate-100">
    <div class="shrink-0 relative">
        @php
            $user = auth()->user();
            $avatarUrl = $user->avatar 
                ? asset('storage/avatars/' . $user->avatar) 
                : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=11d4d4&color=fff';
        @endphp
        <img src="{{ $avatarUrl }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
        <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-slate-800 truncate">{{ $user->name }}</p>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $user->role ?? 'User' }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</aside>