<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 font-sans">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Riwayat Peminjaman</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau seluruh jejak transaksi dan status peminjaman inventaris Anda.</p>
            </div>
            
            <a href="{{ route('warga.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-slate-600 rounded-full font-bold text-xs uppercase tracking-widest border border-slate-200 hover:border-[#11d4d4] hover:text-[#0eb8b8] shadow-sm transition-colors w-fit">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="flex flex-col gap-6">
            @forelse($loans ?? [] as $loan)
                @php
                    // Konfigurasi Status Warna & Ikon
                    $config = [
                        'Pending'  => ['icon' => 'hourglass_empty', 'color' => 'amber', 'label' => 'Menunggu Persetujuan'],
                        'Approved' => ['icon' => 'check_circle', 'color' => 'blue', 'label' => 'Disetujui Admin'],
                        'Active'   => ['icon' => 'ios_share', 'color' => 'indigo', 'label' => 'Sedang Dipinjam'],
                        'Returned' => ['icon' => 'task_alt', 'color' => 'emerald', 'label' => 'Selesai Dikembalikan'],
                        'Rejected' => ['icon' => 'cancel', 'color' => 'rose', 'label' => 'Ditolak'],
                    ];
                    $status = $config[$loan->status] ?? ['icon' => 'info', 'color' => 'slate', 'label' => $loan->status];
                @endphp

                <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                    
                    <div class="absolute top-0 left-0 w-2 h-full bg-{{ $status['color'] }}-400"></div>

                    <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                        
                        <div class="md:w-1/3 flex flex-col gap-4 border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0 md:pr-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Ajuan</p>
                                <p class="text-sm font-bold text-slate-800">{{ $loan->created_at->translatedFormat('l, d M Y') }}</p>
                            </div>
                            
                            <div>
                                <span class="bg-{{ $status['color'] }}-50 text-{{ $status['color'] }}-600 text-[11px] font-black px-3 py-1.5 rounded-full flex items-center gap-1.5 w-fit border border-{{ $status['color'] }}-100">
                                    <span class="material-symbols-outlined text-[16px]">{{ $status['icon'] }}</span>
                                    {{ $status['label'] }}
                                </span>
                            </div>

                            @if(in_array($loan->status, ['Approved', 'Active']))
                                <div class="mt-auto pt-4">
                                    <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Batas Kembali</p>
                                    <p class="text-sm font-bold text-rose-600 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">event_busy</span>
                                        {{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            @elseif($loan->status === 'Returned')
                                <div class="mt-auto pt-4">
                                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Dikembalikan Pada</p>
                                    <p class="text-sm font-bold text-emerald-600 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                                        {{ \Carbon\Carbon::parse($loan->return_date)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="md:w-2/3 flex flex-col">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Daftar Inventaris yang Dipinjam</p>
                            
                            <div class="flex flex-col gap-3">
                                @foreach($loan->details as $detail)
                                    <div class="flex items-center gap-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-white transition-colors">
                                        <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center text-slate-400 shadow-sm shrink-0 overflow-hidden border border-slate-200">
                                            @php
                                                $imgSrc = $detail->item->image 
                                                ? asset('storage/items/' . str_replace('public/', '', $detail->item->image)) 
                                                : 'https://ui-avatars.com/api/?name='.urlencode($detail->item->name).'&background=e2e8f0&color=64748b';
                                            @endphp
                                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-slate-800">{{ $detail->item->name }}</p>
                                            <p class="text-xs font-medium text-slate-500">Kategori: {{ $detail->item->category ?? 'Umum' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($loan->penalty && $loan->penalty->amount > 0)
                                <div class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-start gap-3">
                                    <span class="material-symbols-outlined text-rose-500 text-[20px] mt-0.5">receipt_long</span>
                                    <div>
                                        <p class="text-xs font-black text-rose-700 uppercase tracking-widest mb-0.5">Tagihan Denda Keterlambatan</p>
                                        <p class="text-sm font-bold text-rose-600">Rp {{ number_format($loan->penalty->amount, 0, ',', '.') }} 
                                            <span class="text-xs font-medium text-rose-500">
                                                ({{ $loan->penalty->payment_status === 'Paid' ? 'Sudah Lunas' : 'Belum Dibayar' }})
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-6">
                        <span class="material-symbols-outlined text-5xl">history</span>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 mb-2">Belum Ada Riwayat</h2>
                    <p class="text-sm text-slate-500 max-w-md mb-8">Anda belum pernah melakukan peminjaman barang. Yuk, mulai buat pengajuan peminjaman pertama Anda!</p>
                    <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#11d4d4] text-white rounded-full font-black text-sm uppercase tracking-widest shadow-lg shadow-[#11d4d4]/30 hover:scale-105 transition-all duration-300">
                        <span class="material-symbols-outlined">add_circle</span>
                        Mulai Pinjam
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>