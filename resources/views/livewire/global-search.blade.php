<div class="relative w-full z-50">
    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl pointer-events-none">search</span>
    
    <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-2 pl-10 pr-10 text-sm focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Cari barang atau nama peminjam...">

    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
        <span class="w-4 h-4 rounded-full border-2 border-primary border-t-transparent animate-spin inline-block"></span>
    </div>

    @if(strlen($search) > 2)
        <div class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
            
            @if(count($results['items']) > 0)
                <div class="p-2 bg-slate-50 dark:bg-slate-900/50 text-xs font-bold text-slate-500">Data Barang</div>
                <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($results['items'] as $item)
                        <li class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer">
                            <div class="text-sm font-bold text-primary">{{ $item->name }}</div>
                            <div class="text-xs text-slate-500">Stok: {{ $item->stock }}</div>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if(count($results['loans']) > 0)
                <div class="p-2 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center text-xs font-bold text-slate-500">
                    <span>Transaksi Peminjaman</span>
                </div>
                <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($results['loans'] as $loan)
                        @php
                            // Logika dinamis untuk menentukan rute dan parameter berdasarkan Role
                            $targetRoute = auth()->user()->role == 'petugas' ? 'admin.loans.index' : 'warga.history';
                            $searchParam = auth()->user()->role == 'petugas' ? $loan->user->name : ($loan->details->first()->item->name ?? '');
                        @endphp

                        <a href="{{ route($targetRoute, ['search' => $searchParam]) }}" 
                           class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">
                                    {{ auth()->user()->role == 'petugas' ? $loan->user->name : 'Peminjaman: ' . ($loan->details->first()->item->name ?? 'Barang') }}
                                </span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500">
                                    {{ $loan->status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] text-slate-500">
                                <span class="truncate max-w-[150px]">
                                    {{ $loan->details->count() }} Item Barang
                                </span>
                                <span>{{ \Carbon\Carbon::parse($loan->created_at)->format('d M') }}</span>
                            </div>
                        </a>
                    @endforeach
                </ul>
                
                <a href="{{ auth()->user()->role == 'petugas' ? route('admin.loans.index') : route('warga.history') }}" class="block w-full text-center p-2.5 text-xs font-bold text-primary hover:bg-primary/5 transition-colors border-t border-slate-100 dark:border-slate-700">
                    Lihat semua transaksi...
                </a>
            @endif

            @if(count($results['items']) == 0 && count($results['loans']) == 0)
                <div class="p-4 text-center text-sm text-slate-500">
                    Pencarian tidak menemukan hasil.
                </div>
            @endif
        </div>
    @endif
</div>