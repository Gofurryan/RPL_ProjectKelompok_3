<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kas & Denda') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-1">Laporan Kas & Denda</h1>
            <p class="text-sm text-slate-500">Pantau riwayat pembayaran denda keterlambatan untuk transparansi dan akuntabilitas keuangan kas.</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 border-l-8 border-l-[#11d4d4] flex justify-between items-center mb-10 transition-all hover:shadow-md">
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Kas Masuk (Dari Denda)</p>
                <h3 class="text-4xl font-black text-[#0eb8b8]">
                    Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}
                </h3>
            </div>
            <div class="w-16 h-16 rounded-full bg-[#11d4d4]/10 flex items-center justify-center text-[#0eb8b8]">
                <span class="material-symbols-outlined text-3xl">attach_money</span>
            </div>
        </div>

        <div class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-5 gap-4 px-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">history_toggle_off</span>
                    </div>
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Barang Terlambat Dikembalikan</h2>
                </div>
                <span class="bg-rose-100 text-rose-600 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest shadow-sm border border-rose-200">
                    Perlu Tindakan
                </span>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Nama Barang</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Peminjam</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tgl Kembali</th>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($overdueLoans ?? [] as $loan)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-5 align-middle">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-white transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                                            </div>
                                            <div class="flex flex-col">
                                                {{-- Menampilkan semua barang di dalam peminjaman tersebut --}}
                                                @foreach($loan->details as $detail)
                                                    <span class="font-bold text-slate-800 text-sm">- {{ $detail->item->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-middle">
                                        <span class="text-sm font-bold text-slate-600">{{ $loan->user->name ?? 'Unknown' }}</span>
                                    </td>
                                    <td class="px-6 py-5 align-middle">
                                        <span class="text-sm font-medium text-slate-500">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-8 py-5 align-middle text-right">
                                        <span class="text-sm font-black text-rose-500 bg-rose-50 px-3 py-1.5 rounded-full">
                                            {{ \Carbon\Carbon::parse($loan->due_date)->diffInDays(\Carbon\Carbon::now()) }} Hari
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                                                <span class="material-symbols-outlined text-3xl text-emerald-500">task_alt</span>
                                            </div>
                                            <p class="text-sm font-bold text-slate-500">Luar biasa! Tidak ada barang yang terlambat dikembalikan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <div class="flex items-center gap-3 mb-5 px-2">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shadow-sm border border-slate-200">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                </div>
                <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Riwayat Pembayaran Denda</h2>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Waktu Pembayaran</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Peminjam</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">ID Transaksi</th>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($penalties ?? [] as $penalty)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-5 align-middle">
                                        <span class="text-sm font-medium text-slate-500">
                                            {{ $penalty->updated_at->format('d M Y, H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle">
                                        <span class="text-sm font-bold text-slate-800">{{ $penalty->loan->user->name ?? 'Unknown' }}</span>
                                    </td>
                                    <td class="px-6 py-5 align-middle">
                                        <span class="text-sm font-bold text-[#11d4d4]">
                                            #TRX-{{ str_pad($penalty->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 align-middle text-right">
                                        <span class="text-sm font-black text-emerald-600">
                                            {{ number_format($penalty->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-sm font-bold text-slate-400">
                                        Belum ada riwayat pembayaran denda yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>