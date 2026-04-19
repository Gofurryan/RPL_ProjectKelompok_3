<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 font-sans">

        <div class="mb-10 bg-gradient-to-r from-[#11d4d4] to-[#0eb8b8] rounded-[2.5rem] p-8 sm:p-12 shadow-lg shadow-[#11d4d4]/20 text-white flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">
                    Selamat datang, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-teal-50 font-medium text-sm md:text-base max-w-xl">
                    Sistem Manajemen Aset Terpadu siap membantu kebutuhan acara Anda. Pinjam inventaris dengan mudah, cepat, dan transparan.
                </p>
            </div>
            
            <div class="relative z-10 shrink-0">
    @if($activeLoansCount > 0)
        {{-- Tombol Terkunci karena masih ada transaksi --}}
        <div class="inline-flex items-center gap-2 px-8 py-4 bg-white/20 text-white border border-white/30 rounded-full font-black text-sm uppercase tracking-widest cursor-not-allowed backdrop-blur-sm" title="Selesaikan transaksi sebelumnya untuk meminjam lagi">
            <span class="material-symbols-outlined">lock</span>
            Masih Dipinjam
        </div>
    @else
        {{-- Tombol Aktif --}}
        <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-[#0eb8b8] rounded-full font-black text-sm uppercase tracking-widest shadow-xl hover:scale-105 hover:shadow-2xl transition-all duration-300">
            <span class="material-symbols-outlined">add_circle</span>
            Pinjam Barang
        </a>
    @endif
</div>
        </div>

        @if($activeLoan && in_array($activeLoan->status, ['Approved', 'Active']))
    @php
        $dueDate = \Carbon\Carbon::parse($activeLoan->due_date);
        $isOverdue = $dueDate->isPast() && !$dueDate->isToday();
        $isToday = $dueDate->isToday();
        // Munculkan reminder jika sudah lewat waktu, hari ini, atau sisa 1 hari lagi
        $showReminder = $isOverdue || $isToday || $dueDate->diffInDays(now()) <= 1;
    @endphp

    @if($showReminder)
        <div class="mb-8 p-6 rounded-[2rem] border {{ $isOverdue ? 'bg-rose-50 border-rose-100 text-rose-700' : 'bg-amber-50 border-amber-100 text-amber-700' }} flex items-center gap-4 shadow-sm animate-pulse">
            <div class="w-12 h-12 rounded-full {{ $isOverdue ? 'bg-rose-500' : 'bg-amber-500' }} text-white flex items-center justify-center shrink-0 shadow-lg">
                <span class="material-symbols-outlined text-[24px]">{{ $isOverdue ? 'priority_high' : 'notifications_active' }}</span>
            </div>
            <div class="flex-1">
                <p class="text-[10px] font-black uppercase tracking-widest mb-1">{{ $isOverdue ? 'Peringatan Terlambat' : 'Pengingat Pengembalian' }}</p>
                <p class="text-sm font-bold">
                    @if($isOverdue)
                        Waktu pengembalian <span class="underline">{{ $activeLoan->details->first()->item->name }}</span> sudah lewat! Segera kembalikan ke gudang.
                    @elseif($isToday)
                        Batas waktu pengembalian <span class="underline">{{ $activeLoan->details->first()->item->name }}</span> adalah <span class="font-black">HARI INI</span>.
                    @else
                        Jangan lupa kembalikan <span class="underline">{{ $activeLoan->details->first()->item->name }}</span> sebelum besok, {{ $dueDate->format('d M Y') }}.
                    @endif
                </p>
            </div>
        </div>
    @endif
@endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 shrink-0">
                    <span class="material-symbols-outlined text-[28px]">pending_actions</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Sedang Dipinjam</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $activeLoansCount }} <span class="text-sm font-bold text-slate-500">Transaksi</span></h3>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                    <span class="material-symbols-outlined text-[28px]">task_alt</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Selesai Dikembalikan</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $returnedLoansCount }} <span class="text-sm font-bold text-slate-500">Transaksi</span></h3>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 shrink-0">
                    <span class="material-symbols-outlined text-[28px]">receipt_long</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Tagihan Denda</p>
                    <h3 class="text-2xl font-black {{ $unpaidPenalties > 0 ? 'text-rose-500' : 'text-slate-800' }}">
                        Rp {{ number_format($unpaidPenalties, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-black text-slate-900">Riwayat Peminjaman Terkini</h2>
                <a href="{{ route('warga.history') }}" class="text-xs font-bold text-[#11d4d4] hover:text-[#0eb8b8]">Lihat Semua</a>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($recentLoans as $loan)
                    @php
                        $config = [
                            'Pending'  => ['icon' => 'hourglass_empty', 'color' => 'amber', 'label' => 'Menunggu'],
                            'Approved' => ['icon' => 'check_circle', 'color' => 'blue', 'label' => 'Disetujui'],
                            'Active'   => ['icon' => 'ios_share', 'color' => 'indigo', 'label' => 'Dipinjam'],
                            'Returned' => ['icon' => 'assignment_return', 'color' => 'emerald', 'label' => 'Selesai'],
                            'Rejected' => ['icon' => 'cancel', 'color' => 'rose', 'label' => 'Ditolak'],
                        ];
                        $status = $config[$loan->status] ?? ['icon' => 'info', 'color' => 'slate', 'label' => $loan->status];
                    @endphp

                    <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group">
                        <div class="flex items-center gap-4">
                           <div class="w-12 h-12 rounded-full bg-slate-50 group-hover:bg-white flex items-center justify-center text-slate-500 shadow-sm transition-colors border border-slate-100 overflow-hidden">
                            @php
                                $item = $loan->details->first()->item;
                                $imgSrc = $item->image ? asset('storage/items/' . str_replace('public/', '', $item->image)) 
                                : 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=e2e8f0&color=64748b';
                            @endphp
                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                        </div>
                        <div>
        <p class="text-sm font-bold text-slate-800">
            {{ $item->name ?? 'Barang' }}
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Tanggal Ajuan: {{ $loan->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right flex flex-col items-end gap-1">
                            <span class="bg-{{ $status['color'] }}-50 text-{{ $status['color'] }}-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest border border-{{ $status['color'] }}-100">
                                {{ $status['label'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-sm font-bold text-slate-400">Belum ada riwayat peminjaman.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>