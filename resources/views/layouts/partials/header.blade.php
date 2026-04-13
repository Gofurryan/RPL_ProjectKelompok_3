<header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-8 sticky top-0 z-10">
    <div class="flex-1 max-w-md">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
            <input type="text" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Cari barang atau transaksi...">
        </div>
    </div>
    <div class="flex items-center gap-4">
        <button class="relative p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-2xl">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
        </button>
        <div class="h-8 w-px bg-slate-200 dark:bg-slate-700 mx-2"></div>
        <div class="flex items-center gap-2 text-sm font-medium px-3 py-2">
            <span class="material-symbols-outlined text-primary text-xl">calendar_today</span>
            <span>{{ now()->translatedFormat('d M Y') }}</span>
        </div>
    </div>
</header>