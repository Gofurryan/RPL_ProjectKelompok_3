<div>
    <div class="mb-6 relative w-full md:w-96 ml-auto">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
        <input wire:model.live="search" type="text" 
               class="w-full pl-12 pr-10 py-3 bg-white border-none rounded-full text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#11d4d4]/50 shadow-sm transition-all placeholder-slate-400" 
               placeholder="Cari nama peminjam atau status...">
        
        <div wire:loading wire:target="search" class="absolute right-4 top-1/2 -translate-y-1/2">
            <span class="w-4 h-4 rounded-full border-2 border-[#11d4d4] border-t-transparent animate-spin inline-block"></span>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm relative">
        
        <div wire:loading class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="bg-white px-6 py-3 rounded-full shadow-lg flex items-center gap-3 text-[#0eb8b8] font-bold text-sm">
                <span class="w-5 h-5 rounded-full border-2 border-[#11d4d4] border-t-transparent animate-spin inline-block"></span>
                Memuat data...
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tgl Pengajuan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Peminjam</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest min-w-[300px]">Daftar Barang</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest min-w-[200px]">Jadwal</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            <td class="px-6 py-5 text-sm font-bold text-slate-600 whitespace-nowrap align-top">
                                {{ $loan->created_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-5 align-top">
                                <div class="font-extrabold text-slate-900">{{ $loan->user->name }}</div>
                                <div class="text-xs font-medium text-slate-500 mt-1">{{ $loan->user->email ?? 'Warga' }}</div>
                            </td>

                            <td class="px-6 py-5 align-top">
                                <div class="border border-slate-100 rounded-2xl overflow-hidden bg-slate-50/50">
                                    <ul class="divide-y divide-slate-100">
                                        @foreach($loan->details as $detail)
                                            <li class="p-3 flex justify-between items-center hover:bg-white transition-colors">
                                                <div>
                                                    <span class="text-[10px] font-black text-slate-400 uppercase block tracking-wider mb-0.5">
                                                        [ITM-{{ str_pad($detail->item_id, 3, '0', STR_PAD_LEFT) }}]
                                                    </span>
                                                    <span class="text-sm font-bold text-slate-700">{{ $detail->item->name }}</span>
                                                </div>
                                                <span class="bg-[#11d4d4]/10 text-[#0eb8b8] text-xs font-black px-3 py-1.5 rounded-full whitespace-nowrap">
                                                    {{ $detail->qty ?? $detail->jumlah ?? $detail->quantity ?? 0 }} Unit
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="bg-slate-100/50 p-3 text-right text-xs text-slate-500 font-bold border-t border-slate-100">
                                        Total: <span class="text-slate-800">{{ $loan->details->sum('qty') + $loan->details->sum('jumlah') + $loan->details->sum('quantity') }} Barang</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5 align-top">
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-[#11d4d4]"></span>
                                        <span class="font-bold text-slate-400 w-16">Ambil</span> 
                                        <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                        <span class="font-bold text-slate-400 w-16">Kembali</span> 
                                        <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5 align-top">
                                @php
                                    $statusColors = [
                                        'Pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'Approved' => 'bg-blue-50 text-blue-600 border-blue-200',
                                        'Active' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                        'Returned' => 'bg-slate-50 text-slate-600 border-slate-200',
                                        'Rejected' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        'Cancelled' => 'bg-gray-50 text-gray-500 border-gray-200',
                                    ];
                                    $colorClass = $statusColors[$loan->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                                @endphp
                                <span class="px-4 py-1.5 text-[11px] font-black tracking-wider uppercase rounded-full border {{ $colorClass }}">
                                    {{ $loan->status }}
                                </span>
                            </td>

                            <td class="px-6 py-5 align-top text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    @if($loan->status == 'Pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button class="bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full transition-all border border-emerald-200 hover:border-emerald-500 shadow-sm" title="Setujui">
                                                    <span class="material-symbols-outlined text-[20px]">check</span>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?');">
                                                @csrf @method('PUT')
                                                <button class="bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full transition-all border border-rose-200 hover:border-rose-500 shadow-sm" title="Tolak">
                                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($loan->status == 'Approved')
                                        <form action="{{ route('admin.loans.handover', $loan->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button class="bg-[#11d4d4] hover:bg-[#0eb8b8] text-white px-5 py-2 text-xs font-black uppercase tracking-wider rounded-full shadow-lg shadow-[#11d4d4]/30 transition-all">
                                                Serahkan
                                            </button>
                                        </form>
                                    @elseif($loan->status == 'Active')
                                        <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 text-xs font-black uppercase tracking-wider rounded-full shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px]">keyboard_return</span> Kembali
                                            </button>
                                        </form>
                                    @elseif($loan->status == 'Returned')
                                        @if($loan->penalty)
                                            <div class="flex flex-col items-center gap-1.5 bg-rose-50 p-2.5 rounded-2xl border border-rose-100">
                                                <span class="text-xs font-black text-rose-600">Denda: Rp {{ number_format($loan->penalty->amount, 0, ',', '.') }}</span>
                                                @if($loan->penalty->payment_status == 'Paid')
                                                    <span class="bg-emerald-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider">Lunas</span>
                                                @else
                                                    <form action="{{ route('admin.penalties.pay', $loan->penalty->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <button class="bg-white hover:bg-rose-600 text-rose-600 hover:text-white px-4 py-1.5 text-[10px] font-black uppercase tracking-wider rounded-full transition-colors border border-rose-200 shadow-sm">
                                                            Konfirmasi
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @else
                                            <div class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-2xl border border-emerald-100">
                                                <span class="font-black text-xs uppercase tracking-wider block">Selesai</span>
                                                <span class="text-[10px] font-bold opacity-75">Tepat Waktu</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-slate-300 font-bold">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-4xl text-slate-300">inbox</span>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500">Belum ada data peminjaman.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 bg-white border-t border-slate-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                
                <div class="text-xs font-bold text-slate-400">
                    @if($loans->total() > 0)
                        Menampilkan {{ $loans->firstItem() }} 
                        sampai {{ $loans->lastItem() }}
                        dari <span class="text-[#11d4d4]">{{ $loans->total() }}</span> data
                    @endif
                </div>

                <div class="pagination-custom">
                    {{ $loans->links() }}
                </div>
            </div>
        </div>

        <style>
            /* 1. SEMBUNYIKAN TEKS "SHOWING OF RESULTS" BAWAAN LARAVEL */
            .pagination-custom nav p.leading-5,
            .pagination-custom nav > div.sm\:flex-1 > div:first-child {
                display: none !important;
            }

            /* 2. DORONG TOMBOL PAGINATION FULL KE KANAN */
            .pagination-custom nav > div.sm\:flex-1 {
                display: flex !important;
                justify-content: flex-end !important;
                width: 100% !important;
            }
        /* Gaya Kustom untuk Pagination agar berwarna Tema #11d4d4 */
        .pagination-custom nav span[aria-current="page"] span {
            background-color: #11d4d4 !important;
            border-color: #11d4d4 !important;
            color: white !important;
        }
        .pagination-custom nav a:hover {
            color: #11d4d4 !important;
        }
    </style>
    </div>
</div>