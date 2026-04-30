<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Inventaris') }}
        </h2>
    </x-slot>

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

    <div class="mb-6 space-y-6" x-data="{ selectedItem: null }">

        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Data Barang</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 mb-3">Kelola dan pantau seluruh aset operasional tempat ibadah</p>
                
                <!-- KOTAK NOTIFIKASI SUKSES (Warna Hijau) -->
@if (session('success'))
    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-start justify-between gap-3 shadow-sm">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-emerald-500 text-xl shrink-0">check_circle</span>
            <div>
                <h4 class="text-sm font-black text-emerald-700 mb-0.5">Berhasil!</h4>
                <p class="text-xs font-medium text-emerald-600 leading-snug">
                    {{ session('success') }}
                </p>
            </div>
        </div>
        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
@endif

<!-- KOTAK NOTIFIKASI ERROR / GAGAL (Warna Merah/Rose) -->
@if (session('error'))
    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-start justify-between gap-3 shadow-sm">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-rose-500 text-xl shrink-0">error</span>
            <div>
                <h4 class="text-sm font-black text-rose-700 mb-0.5">Tindakan Ditolak</h4>
                <p class="text-xs font-medium text-rose-600 leading-snug">
                    {{ session('error') }}
                </p>
            </div>
        </div>
        <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
@endif
            </div>

            <div class="flex items-center gap-3">
                    <a href="{{ route('items.export', request()->query()) }}" class="flex items-center gap-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white px-5 py-2.5 rounded-full text-sm font-bold transition-all border border-emerald-100 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Export Data
                    </a>
                                    
                <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 bg-[#11d4d4] hover:bg-[#0eb8b8] text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors shadow-sm shadow-[#11d4d4]/20">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Barang
                </a>
            </div>
        </div>

        <form action="{{ route('items.index') }}" method="GET" class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
    <div class="flex flex-col lg:flex-row items-end gap-4">
        
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-slate-500 mb-2 ml-4">Cari Barang</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Masukkan nama barang atau ID..." class="w-full bg-slate-50 border-none text-sm rounded-full py-3 pl-12 pr-6 focus:ring-2 focus:ring-[#11d4d4]/50 transition-all">
            </div>
        </div>

        <div class="w-full lg:w-48">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 ml-4">Kategori</label>
            <div class="relative">
                <select name="category" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-full py-3 px-4 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <option value="Elektronik" {{ request('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Furniture" {{ request('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="Perlengkapan Ibadah" {{ request('category') == 'Perlengkapan Ibadah' ? 'selected' : '' }}>Perlengkapan Ibadah</option>
                    <option value="Sarana Umum" {{ request('category') == 'Sarana Umum' ? 'selected' : '' }}>Sarana Umum</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-lg">expand_more</span>
            </div>
        </div>

        <div class="w-full lg:w-48">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 ml-4">Status</label>
            <div class="relative">
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-full py-3 px-4 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 focus:border-[#11d4d4] transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Borrowed" {{ request('status') == 'Borrowed' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="Damaged" {{ request('status') == 'Damaged' ? 'selected' : '' }}>Rusak</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-lg">expand_more</span>
            </div>
        </div>

        <button type="submit" class="w-full lg:w-auto bg-[#11d4d4]/10 hover:bg-[#11d4d4]/20 text-[#0eb8b8] font-bold px-6 py-3 rounded-full text-sm transition-colors lg:mt-0 whitespace-nowrap">
            Terapkan
        </button>
        
        @if(request()->anyFilled(['search', 'category', 'status']))
            <a href="{{ route('items.index') }}" class="w-full lg:w-auto bg-rose-50 hover:bg-rose-100 text-rose-500 font-bold px-4 py-3 rounded-full text-sm transition-colors lg:mt-0 whitespace-nowrap text-center">
                <span class="material-symbols-outlined text-[18px] align-middle">close</span>
            </a>
        @endif
    </div>
</form>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-5 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Foto</th>
                            <th class="px-6 py-5 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Nama Barang</th>
                            <th class="px-6 py-5 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Kategori</th>
                            <th class="px-6 py-5 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-5 text-[11px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($items as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 border border-slate-100 shadow-sm">
                                    @if($item->image)
                                        <img src="{{ asset('storage/items/'.$item->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <span class="material-symbols-outlined">inventory_2</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800">{{ $item->name }}</div>
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tighter">ID: {{ $item->item_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $item->category ?? '-' }}</td>
                            <td class="px-6 py-4">
                            @php
                                // Pemetaan warna berdasarkan status
                                $statusLower = strtolower($item->status);
                                if (str_contains($statusLower, 'tersedia') || $statusLower == 'available') {
                                    $badgeColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                    $label = 'Tersedia';
                                } elseif (str_contains($statusLower, 'pinjam') || $statusLower == 'borrowed') {
                                    $badgeColor = 'bg-orange-50 text-orange-600 border-orange-100';
                                    $label = 'Dipinjam';
                                } elseif (str_contains($statusLower, 'rusak') || $statusLower == 'damaged') {
                                    $badgeColor = 'bg-rose-50 text-rose-600 border-rose-100';
                                    $label = 'Rusak';
                                } elseif (str_contains($statusLower, 'mainten') || $statusLower == 'perbaikan') {
                                    $badgeColor = 'bg-sky-50 text-sky-600 border-sky-100';
                                    $label = 'Maintenance';
                                } else {
                                    $badgeColor = 'bg-slate-50 text-slate-500 border-slate-100';
                                    $label = $item->status;
                                }
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full border {{ $badgeColor }}">
                                {{ $label }}
                            </span>
                        </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="selectedItem = {{ json_encode($item) }}" class="p-2 text-slate-400 hover:text-[#11d4d4] hover:bg-[#11d4d4]/10 rounded-full transition-all" title="Detail Barang">
                                        <span class="material-symbols-outlined text-[20px]">info</span>
                                    </button>
                                    
                                    <a href="{{ route('items.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition-all">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-5 bg-slate-50/30 border-t border-slate-50 flex items-center justify-between">
                <div class="text-xs font-bold text-slate-400">
                    @if($items->total() > 0)
                        Menampilkan {{ $items->firstItem() }} 
                        sampai {{ $items->lastItem() }}
                        dari <span class="text-[#11d4d4]">{{ $items->total() }}</span> barang
                    @endif
                </div>
                <div class="pagination-custom">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="selectedItem" 
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                 style="display: none;" 
                 x-cloak>
                 
                <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-sm" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="selectedItem = null">
                </div>

                <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                     
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900" x-text="selectedItem.name"></h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest" x-text="'ID: ' + selectedItem.item_code"></p>
                            </div>
                            <button @click="selectedItem = null" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors">
                                <span class="material-symbols-outlined text-lg">close</span>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-3xl">
                                <div class="w-10 h-10 rounded-2xl bg-[#11d4d4]/10 flex items-center justify-center text-[#11d4d4]">
                                    <span class="material-symbols-outlined">inventory_2</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Stok Tersedia</p>
                                    <p class="text-sm font-bold text-slate-700" x-text="selectedItem.stock + ' Unit'"></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-3xl">
                                <div class="w-10 h-10 rounded-2xl bg-[#11d4d4]/10 flex items-center justify-center text-[#11d4d4]">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Lokasi Penyimpanan</p>
                                    <p class="text-sm font-bold text-slate-700" x-text="selectedItem.location || 'Tidak ditentukan'"></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-3xl">
                                <div class="w-10 h-10 rounded-2xl bg-[#11d4d4]/10 flex items-center justify-center text-[#11d4d4]">
                                    <span class="material-symbols-outlined">health_and_safety</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Kondisi Fisik</p>
                                    <p class="text-base font-bold text-slate-700 capitalize" 
                                        x-text="{
                                        'good': 'Baik',
                                        'fair': 'Layak',
                                        'poor': 'Buruk'
                                        }[selectedItem.condition] || selectedItem.condition || '-'">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button @click="selectedItem = null" class="w-full mt-8 py-3 bg-[#11d4d4] text-white font-bold rounded-full hover:bg-[#0eb8b8] transition-all shadow-lg shadow-[#11d4d4]/20 uppercase tracking-wide text-sm">
                            Tutup Detail
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>