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

    <!-- ERROR MESSAGE -->
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
                        <div class="flex-1 w-full">
                            <label class="text-x font-bold text-slate-500 uppercase tracking-wider mb-2 block">Pilih Barang</label>
                            <div class="relative">
                                <select x-model="selectedItem" class="w-full pl-4 pr-12 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary appearance-none transition-all">
                                    <option value="" disabled selected>-- Pilih barang yang tersedia --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" data-name="{{ $item->name }}" data-stock="{{ $item->stock }}">
                                            {{ $item->name }} (Sisa Stok: {{ $item->stock }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-32">
                            <label class="text-x font-bold text-slate-500 uppercase tracking-wider mb-2 block">Jumlah</label>
                            <input type="number" x-model="quantity" min="1" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        
                        <button @click="addToCart()" type="button" class="w-full md:w-auto px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 active:scale-95 transition-all flex items-center justify-center gap-2 shadow-md shadow-primary/20">
                            <span class="material-symbols-outlined text-sm">add</span> Tambah
                        </button>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            <template x-for="(item, index) in cart" :key="index">
                                <li class="flex justify-between items-center p-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="item.name"></span>
                                    <div class="flex items-center gap-4">
                                        <span class="text-primary font-bold bg-primary/10 px-3 py-1 rounded-full text-xs" x-text="item.qty + ' Unit'"></span>
                                        <button @click="removeFromCart(index)" type="button" class="text-red-400 hover:text-red-600 p-1 rounded-md hover:bg-red-50 transition-colors flex items-center">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </div>
                                </li>
                            </template>
                            <li x-show="cart.length === 0" class="text-center text-slate-400 text-sm py-6 bg-slate-50/50 dark:bg-slate-800/50">
                                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">production_quantity_limits</span>
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
                                class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary text-sm transition-all">
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Rencana Kembali</label>
                            <div>
                                <input 
                                name="due_date" 
                                type="datetime-local" 
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
                quantity: 1,
                errorMessage: '',

                addToCart() {
                    // Reset error message setiap kali tombol diklik
                    this.errorMessage = '';

                    if (!this.selectedItem || this.quantity < 1) {
                        this.errorMessage = 'Silakan pilih barang dan masukkan jumlah yang valid.';
                        return;
                    }

                    let selectEl = document.querySelector('select[x-model="selectedItem"]');
                    let selectedOption = selectEl.options[selectEl.selectedIndex];
                    let itemName = selectedOption.getAttribute('data-name');
                    let maxStock = parseInt(selectedOption.getAttribute('data-stock'));

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

                    // Reset field form
                    this.selectedItem = '';
                    this.quantity = 1;
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.errorMessage = ''; // Bersihkan error jika user menghapus barang
                }
            }))
        })
    </script>
</x-app-layout>