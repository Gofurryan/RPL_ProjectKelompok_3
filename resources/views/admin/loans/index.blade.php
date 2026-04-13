<x-app-layout>
    <div class="flex flex-col gap-6 max-w-7xl mx-auto pb-10">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Manajemen Peminjaman</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola persetujuan, serah terima, dan pengembalian barang inventaris.</p>
            </div>
            <div class="relative w-full md:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary shadow-sm" placeholder="Cari peminjam...">
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Tgl Pengajuan</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider min-w-[300px]">Daftar Barang & Qty</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider min-w-[200px]">Jadwal</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap align-top">
                                    {{ $loan->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-slate-900 dark:text-slate-100">{{ $loan->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $loan->user->email ?? 'Warga' }}</div>
                                </td>

                                <td class="px-6 py-5 align-top">
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden bg-white dark:bg-slate-800 shadow-sm">
                                        <ul class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                            @foreach($loan->details as $detail)
                                            <li class="p-3 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                                <div>
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase block tracking-wider">
                                                        [ITM-{{ str_pad($detail->item_id, 3, '0', STR_PAD_LEFT) }}]
                                                    </span>
                                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $detail->item->name }}</span>
                                                </div>
                                                <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-2.5 py-1 rounded-md whitespace-nowrap">
                                                    {{ $detail->qty ?? $detail->jumlah ?? $detail->quantity ?? 0 }} Unit
                                                </span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 text-right text-xs text-slate-500 font-bold border-t border-slate-100 dark:border-slate-700">
                                            Total: {{ $loan->details->sum('qty') + $loan->details->sum('jumlah') + $loan->details->sum('quantity') }} Barang
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5 align-top">
                                    <div class="space-y-1.5 text-sm">
                                        <div class="flex items-start gap-2">
                                            <span class="font-bold text-emerald-600 dark:text-emerald-500 w-16">Ambil:</span> 
                                            <span class="text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <span class="font-bold text-rose-600 dark:text-rose-500 w-16">Kembali:</span> 
                                            <span class="text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5 align-top">
                                    @php
                                        $statusColors = [
                                            'Pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'Approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Active' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                            'Returned' => 'bg-slate-100 text-slate-700 border-slate-200',
                                            'Rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            'Cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
                                        ];
                                        $colorClass = $statusColors[$loan->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $colorClass }}">
                                        {{ $loan->status }}
                                    </span>
                                </td>

                                <td class="px-6 py-5 align-top text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        @if($loan->status == 'Pending')
                                            <div class="flex gap-2">
                                                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button class="bg-emerald-500 hover:bg-emerald-600 text-white p-1.5 rounded-lg shadow-sm transition-colors" title="Setujui">
                                                        <span class="material-symbols-outlined text-sm">check</span>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?');">
                                                    @csrf @method('PUT')
                                                    <button class="bg-rose-500 hover:bg-rose-600 text-white p-1.5 rounded-lg shadow-sm transition-colors" title="Tolak">
                                                        <span class="material-symbols-outlined text-sm">close</span>
                                                    </button>
                                                </form>
                                            </div>

                                        @elseif($loan->status == 'Approved')
                                            <form action="{{ route('admin.loans.handover', $loan->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 text-xs font-bold rounded-lg shadow-sm transition-colors">
                                                    Serahkan Barang
                                                </button>
                                            </form>

                                        @elseif($loan->status == 'Active')
                                            <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-sm">keyboard_return</span> Kembali
                                                </button>
                                            </form>

                                        @elseif($loan->status == 'Returned')
                                            @if($loan->penalty)
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="text-sm font-black text-rose-600">Denda: Rp {{ number_format($loan->penalty->amount, 0, ',', '.') }}</span>
                                                    @if($loan->penalty->payment_status == 'Paid')
                                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Lunas</span>
                                                    @else
                                                        <form action="{{ route('admin.penalties.pay', $loan->penalty->id) }}" method="POST" class="mt-1">
                                                            @csrf @method('PUT')
                                                            <button class="bg-rose-100 hover:bg-rose-200 text-rose-700 px-3 py-1 text-[10px] font-black uppercase rounded-lg transition-colors border border-rose-200">
                                                                Konfirmasi Bayar
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-emerald-600 font-bold text-sm text-center">Selesai<br><span class="text-xs font-medium">(Tepat Waktu)</span></span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <span class="material-symbols-outlined text-5xl mb-2 opacity-50">inbox</span>
                                        <p class="text-sm">Belum ada data peminjaman yang masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($loans, 'links'))
                <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>