<div>
    <div class="mb-4 relative w-full md:w-72 ml-auto">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
        <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary shadow-sm transition-all" placeholder="Cari nama peminjam atau status...">
        
        <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
            <span class="w-4 h-4 rounded-full border-2 border-primary border-t-transparent animate-spin inline-block"></span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto relative">
            
            <div wire:loading class="absolute inset-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-[2px] z-10 flex items-center justify-center">
                <span class="font-bold text-primary">Mencari data...</span>
            </div>

            <table class="w-full text-left border-collapse">
                </table>
        </div>
        
        <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            {{ $loans->links() }}
        </div>
    </div>
</div>