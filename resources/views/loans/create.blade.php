<x-app-layout>
    <div class="flex flex-col gap-6 max-w-4xl mx-auto">
        
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-slate-100">Form Booking Barang</h1>
            <p class="text-slate-500 dark:text-slate-400">Silakan pilih barang dan lengkapi jadwal peminjaman inventaris.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 p-4 rounded-xl shadow-sm border border-red-200">
                <ul class="list-disc pl-5 text-sm font-medium text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div x-data="cartSystem()">

            <div x-show="errorMessage" x-transition class="mb-6 bg-red-50 p-4 rounded-xl border border-red-200">
                <ul class="list-disc pl-5 text-sm font-medium text-red-600">
                    <li x-text="errorMessage"></li>
                </ul>
            </div>

            <div class="flex flex-col gap-8">
                
                <div class="bg-slate-50 dark:bg-slate-900 p-5 md:p-6 rounded-xl border border-slate-200 dark:border-slate-700">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-3 rounded-lg">shopping_cart</span>
                        Keranjang Peminjaman
                    </h3>
                    
                    <div class="flex flex-col md:flex-row gap-4 items-end mb-6">
                        
                        <div class="flex-1 w-full relative" x-data="{ openDropdown: false }">
                            <label class="text-x font-bold text-slate-500 uppercase tracking-wider mb-2 block">Pilih Barang</label>
                            
                            <button @click="openDropdown = !openDropdown" type="button" class="w-full pl-4 pr-12 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all flex items-center justify-between min-h-[50px] shadow-sm">
                                
                                <template x-if="!selectedItem">
                                    <span class="text-slate-400 font-medium truncate">-- Pilih barang yang tersedia --</span>
                                </template>

                                <template x-if="selectedItem">
                                    <div class="flex items-center gap-3">
                                        <img :src="selectedItemImage" class="w-8 h-8 object-cover rounded shadow-sm border border-slate-100 dark:border-slate-600">
                                        <span class="font-bold text-slate-700 dark:text-slate-200" x-text="selectedItemName"></span>
                                    </div>
                                </template>

                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none transition-transform" :class="openDropdown ? 'rotate-180' : ''">expand_more</span>
                            </button>

                            <div x-show="openDropdown" @click.away="openDropdown = false" x-transition.opacity.duration.200ms class="absolute z-30 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl rounded-xl max-h-60 overflow-y-auto py-2">
                                @foreach($items as $item)
                                    @php
                                        $cleanPath = str_replace('public/', '', $item->image);
                                        $imgSrc = $item->image ? asset('storage/items/' . $cleanPath) : 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=e2e8f0&color=64748b';
                                    @endphp
                                    <div @click="
                                            selectedItem = '{{ $item->id }}'; 
                                            selectedItemName = '{{ addslashes($item->name) }}'; 
                                            selectedItemStock = {{ $item->stock }};
                                            selectedItemImage = '{{ $imgSrc }}';
                                            openDropdown = false;
                                         " 
                                         class="cursor-pointer px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-4 border-b border-slate-50 dark:border-slate-700/50 last:border-0 transition-colors">
                                        
                                        <img src="{{ $imgSrc }}" class="w-11 h-11 object-cover rounded-md border border-slate-200 dark:border-slate-600 shadow-sm">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $item->name }}</span>
                                            <span class="text-primary font-bold text-[10px] uppercase tracking-widest mt-0.5">Sisa Stok: {{ $item->stock }} Unit</span>
                                        </div>
                                    </div>
                                @endforeach

                                @if($items->isEmpty())
                                    <div class="px-4 py-4 text-sm text-center text-slate-500 font-medium">Tidak ada barang tersedia.</div>
                                @endif
                            </div>
                        </div>
                        <div class="w-full md:w-32">
                            <label class="text-x font-bold text-slate-500 uppercase tracking-wider mb-2 block">Jumlah</label>
                            <input type="number" x-model="quantity" min="1" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all min-h-[50px]">
                        </div>
                        
                        <button @click="addToCart()" type="button" class="w-full md:w-auto px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 active:scale-95 transition-all flex items-center justify-center gap-2 shadow-md shadow-primary/20 min-h-[50px]">
                            <span class="material-symbols-outlined text-sm">add</span> Tambah
                        </button>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            <template x-for="(item, index) in cart" :key="index">
                                <li class="flex justify-between items-center p-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <span class="font-bold text-slate-700 dark:text-slate-300" x-text="item.name"></span>
                                    <div class="flex items-center gap-4">
                                        <span class="text-primary font-bold bg-primary/10 px-3 py-1 rounded-full text-xs shadow-sm border border-primary/20" x-text="item.qty + ' Unit'"></span>
                                        <button @click="removeFromCart(index)" type="button" class="text-red-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition-colors flex items-center">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </div>
                                </li>
                            </template>
                            <li x-show="cart.length === 0" class="text-center text-slate-400 text-sm py-8 bg-slate-50/50 dark:bg-slate-800/50">
                                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-3">production_quantity_limits</span>
                                Keranjang masih kosong. Silakan tambah barang di atas.
                            </li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('loans.store') }}" method="POST" class="flex flex-col gap-6 border-t border-slate-100 dark:border-slate-700 pt-6">
                    @csrf
                    
                    <template x-for="(item, index) in cart" :key="index">
                        <div>
                            <input type="hidden" :name="`items[${index}][id]`" :value="item.id">
                            <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty">
                        </div>
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Ambil</label>
                            <div>
                                <input 
                                name="loan_date" 
                                type="datetime-local" 
                                required
                                class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary text-sm transition-all">
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Rencana Kembali</label>
                            <div>
                                <input 
                                name="due_date" 
                                type="datetime-local" 
                                required
                                class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <button type="submit" :disabled="cart.length === 0" :class="cart.length === 0 ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-primary hover:bg-primary/90 active:scale-[0.98] shadow-lg shadow-primary/30'" class="mt-4 w-full text-white font-bold py-4 rounded-xl transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">send</span>
                        Ajukan Peminjaman
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartSystem', () => ({
                cart: [],
                selectedItem: '',
                selectedItemName: '',     // Tambahan memori nama barang
                selectedItemStock: 0,     // Tambahan memori sisa stok
                selectedItemImage: '',    // Tambahan memori gambar
                quantity: 1,
                errorMessage: '',

                addToCart() {
                    // Reset error message setiap kali tombol diklik
                    this.errorMessage = '';

                    if (!this.selectedItem || this.quantity < 1) {
                        this.errorMessage = 'Silakan pilih barang dan masukkan jumlah yang valid.';
                        return;
                    }

                    // Kita tidak butuh document.querySelector lagi, langsung ambil dari memori
                    let itemName = this.selectedItemName;
                    let maxStock = this.selectedItemStock;

                    let existingItem = this.cart.find(i => i.id === this.selectedItem);

                    if (existingItem) {
                        if (existingItem.qty + parseInt(this.quantity) > maxStock) {
                            this.errorMessage = `Sistem Menolak: Jumlah total "${itemName}" di keranjang melebihi sisa stok (${maxStock} unit).`;
                            return;
                        }
                        existingItem.qty += parseInt(this.quantity);
                    } else {
                        if (parseInt(this.quantity) > maxStock) {
                            this.errorMessage = `Sistem Menolak: Anda meminta ${this.quantity} unit, tapi sisa stok "${itemName}" hanya ${maxStock} unit.`;
                            return;
                        }
                        this.cart.push({
                            id: this.selectedItem,
                            name: itemName,
                            qty: parseInt(this.quantity)
                        });
                    }

                    // Reset kotak pilihan dan jumlah
                    this.selectedItem = '';
                    this.selectedItemName = '';
                    this.selectedItemStock = 0;
                    this.selectedItemImage = '';
                    this.quantity = 1;
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.errorMessage = ''; 
                }
            }))
        })
    </script>
</x-app-layout>