<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 font-sans">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Dashboard Utama</h1>
                <p class="text-sm text-slate-500 mt-1">Selamat datang di sistem manajemen inventaris.</p>
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="hidden md:block pl-4 border-l border-slate-200">
                    <span class="text-lg font-black text-slate-700">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F') }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            @php
                // Array data untuk mempermudah perulangan pembuatan kartu
                $cards = [
                    ['title' => 'Total Barang', 'count' => $totalItems, 'trend' => $trendTotal, 'icon' => 'inventory_2', 'color' => '#11d4d4', 'bg' => 'bg-[#11d4d4]/10'],
                    ['title' => 'Tersedia', 'count' => $availableItems, 'trend' => $trendAvailable, 'icon' => 'check_circle', 'color' => '#10b981', 'bg' => 'bg-emerald-50'],
                    ['title' => 'Dipinjam', 'count' => $borrowedItems, 'trend' => $trendBorrowed, 'icon' => 'sync_alt', 'color' => '#6366f1', 'bg' => 'bg-indigo-50'],
                    ['title' => 'Maintenance', 'count' => $maintenanceItems, 'trend' => $trendMaintenance, 'icon' => 'build', 'color' => '#f59e0b', 'bg' => 'bg-amber-50'],
                ];
            @endphp

            @foreach($cards as $card)
                @php
                    // Logika penentu warna dan panah tren
                    if ($card['trend'] > 0) {
                        $trendTheme = 'bg-emerald-50 text-emerald-600';
                        $trendIcon = 'trending_up';
                        $trendSign = '+';
                    } elseif ($card['trend'] < 0) {
                        $trendTheme = 'bg-rose-50 text-rose-600';
                        $trendIcon = 'trending_down';
                        $trendSign = ''; // Tidak perlu plus karena angka minus sudah bawa tanda '-'
                    } else {
                        $trendTheme = 'bg-slate-50 text-slate-500';
                        $trendIcon = 'horizontal_rule';
                        $trendSign = '';
                    }
                @endphp

                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex flex-col relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 rounded-full {{ $card['bg'] }} flex items-center justify-center" style="color: {{ $card['color'] }}">
                            <span class="material-symbols-outlined text-[24px]">{{ $card['icon'] }}</span>
                        </div>
                        
                        <span class="{{ $trendTheme }} text-[10px] font-black px-2 py-1 rounded-full flex items-center gap-0.5 shadow-sm">
                            <span class="material-symbols-outlined text-[12px]">{{ $trendIcon }}</span>
                            {{ $trendSign }}{{ $card['trend'] }}%
                        </span>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">{{ $card['title'] }}</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ number_format($card['count']) }}</h3>
                </div>
            @endforeach

        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">Stok per Kategori</h2>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">Jumlah item tersedia saat ini</p>
                    </div>
                    <button class="text-slate-400 hover:text-[#11d4d4] transition-colors">
                        <span class="material-symbols-outlined">more_horiz</span>
                    </button>
                </div>

                <div class="flex flex-col gap-5 flex-1 justify-center">
                    @forelse($categories as $cat)
                        @php
                            // Mengatur opasitas warna agar bergradasi (bar pertama paling pekat, bar terakhir paling muda)
                            $opacities = ['bg-[#11d4d4]', 'bg-[#11d4d4]/80', 'bg-[#11d4d4]/60', 'bg-[#11d4d4]/40'];
                            $colorClass = $opacities[$loop->index] ?? 'bg-[#11d4d4]/30';
                            $barWidth = ($cat->total / $maxCategoryCount) * 100;
                        @endphp
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-slate-800">{{ $cat->category }}</span>
                                <span class="text-xs font-bold text-slate-400">{{ $cat->total }} Unit</span>
                            </div>
                            <div class="w-full bg-slate-50 rounded-full h-3.5">
                                <div class="{{ $colorClass }} h-3.5 rounded-full transition-all duration-1000" style="width: {{ $barWidth }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 font-medium text-center">Belum ada data kategori.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">Status Barang</h2>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">Kondisi operasional aset</p>
                    </div>
                    <select class="text-xs font-bold text-slate-600 bg-slate-50 border-none rounded-xl px-3 py-1.5 focus:ring-0 cursor-pointer outline-none">
                        <option>Bulan Ini</option>
                        <option>Bulan Lalu</option>
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-10 flex-1">
                    
                    @php
                        // Hitung derajat untuk lingkaran (Total 360 derajat)
                        $degBaik = ($pctBaik / 100) * 360;
                        $degServis = $degBaik + (($pctServis / 100) * 360);
                    @endphp
                    
                    <div class="relative w-48 h-48 rounded-full flex items-center justify-center bg-slate-50 p-2 shadow-inner">
                        <div class="w-full h-full rounded-full" 
                             style="background: conic-gradient(#11d4d4 0deg {{ $degBaik }}deg, #fbbf24 {{ $degBaik }}deg {{ $degServis }}deg, #f43f5e {{ $degServis }}deg 360deg);">
                            
                            <div class="absolute inset-4 bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                                <span class="text-3xl font-black text-slate-900">{{ $totalItems }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Total Aset</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-3.5 h-3.5 rounded-full bg-[#11d4d4] shadow-sm"></span>
                            <span class="text-sm font-bold text-slate-800 w-24">Baik</span>
                            <span class="text-sm font-black text-slate-400">({{ $pctBaik }}%)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-3.5 h-3.5 rounded-full bg-amber-400 shadow-sm"></span>
                            <span class="text-sm font-bold text-slate-800 w-24">Perlu Servis</span>
                            <span class="text-sm font-black text-slate-400">({{ $pctServis }}%)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-3.5 h-3.5 rounded-full bg-rose-500 shadow-sm"></span>
                            <span class="text-sm font-bold text-slate-800 w-24">Rusak</span>
                            <span class="text-sm font-black text-slate-400">({{ $pctRusak }}%)</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-lg font-black text-slate-900">Peminjaman Terkini</h2>
                    
                    <div class="flex bg-slate-50 p-1 rounded-full">
                        <a href="{{ route('admin.dashboard', ['range' => 7]) }}" 
                           class="px-4 py-1.5 {{ $range == 7 ? 'bg-[#11d4d4] text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }} text-xs font-bold rounded-full transition-colors">
                            7 Hari
                        </a>
                        <a href="{{ route('admin.dashboard', ['range' => 30]) }}" 
                           class="px-4 py-1.5 {{ $range == 30 ? 'bg-[#11d4d4] text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }} text-xs font-bold rounded-full transition-colors">
                            30 Hari
                        </a>
                    </div>
                </div>

                <div class="flex-1 flex items-end justify-between gap-2 sm:gap-4 mt-auto pt-10 h-64">
                    
                    @foreach($chartData as $day => $count)
                        @php
                            // Hitung persentase tinggi batang (Minimal 5% agar bentuk lengkungnya tetap terlihat walau 0)
                            $height = $count > 0 ? ($count / $maxCount) * 100 : 5;
                            $isActive = $day === $currentDay;
                        @endphp
                        
                        <div class="flex flex-col items-center gap-3 w-full h-full justify-end group">
                            <div class="w-full max-w-[40px] {{ $isActive ? 'bg-[#11d4d4] shadow-lg shadow-[#11d4d4]/30' : 'bg-[#11d4d4]/20 hover:bg-[#11d4d4]/40' }} rounded-full transition-all duration-500 cursor-pointer relative" 
                                 style="height: {{ $height }}%">
                                
                                <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] font-bold py-1.5 px-3 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none shadow-md">
                                    {{ $count }} Transaksi
                                </span>
                            </div>
                            <span class="text-xs {{ $isActive ? 'font-black text-[#0eb8b8]' : 'font-bold text-slate-400' }}">{{ $day }}</span>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-black text-slate-900">Aktivitas Terbaru</h2>
                </div>
                <div class="flex flex-col gap-4">
                    @forelse($latestActivities as $log)
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#11d4d4]/10 text-[#11d4d4] flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-800">
                                    {{ $log->user->name }}
                                </p>
                                <p class="text-xs text-slate-500">{{ $log->description }}</p>
                            </div>
                            <div class="text-[10px] text-slate-400 font-medium italic">
                                {{ $log->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Belum ada aktivitas tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>