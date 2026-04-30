<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Inventaris') }}
        </h2>
    </x-slot>

    <div class="mb-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Edit Detail Barang</h1>
                <p class="text-sm text-slate-500 mt-1">ID Barang: <span class="font-bold text-[#11d4d4]">{{ $item->item_code }}</span></p>
            </div>
            <a href="{{ route('items.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-full text-sm hover:bg-slate-50 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data"
              x-data="{ 
                  // Muat gambar lama dari database jika ada
                  imageUrl: '{{ $item->image ? asset('storage/items/'.$item->image) : '' }}',
                  fileChosen(event) {
                      const file = event.target.files[0];
                      if (!file) return;
                      const reader = new FileReader();
                      reader.readAsDataURL(file);
                      reader.onload = e => this.imageUrl = e.target.result;
                  }
              }" 
              class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100">
            
            @csrf

            @if ($errors->any())
        <div class="mb-8 p-6 bg-rose-50 border border-rose-200 rounded-3xl">
            <div class="flex items-center gap-2 text-rose-700 font-black mb-3 text-sm uppercase tracking-widest">
                <span class="material-symbols-outlined text-rose-500">warning</span>
                Penyebab Gagal Simpan:
            </div>
            <ul class="list-disc list-inside text-sm font-bold text-rose-500 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
            @method('PUT') <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <div class="lg:col-span-8 space-y-6">
                    
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 focus:ring-2 focus:ring-[#11d4d4]/50 transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Kategori <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="category" required class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 cursor-pointer">
                                    <option value="Elektronik" {{ $item->category == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="Furniture" {{ $item->category == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="Perlengkapan Ibadah" {{ $item->category == 'Perlengkapan Ibadah' ? 'selected' : '' }}>Perlengkapan Ibadah</option>
                                    <option value="Sarana Umum" {{ $item->category == 'Sarana Umum' ? 'selected' : '' }}>Sarana Umum</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Status <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="status" required class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 cursor-pointer">
                                    <option value="Available" {{ $item->status == 'Available' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="Borrowed" {{ $item->status == 'Borrowed' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="Maintenance" {{ $item->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="Damaged" {{ $item->status == 'Damaged' ? 'selected' : '' }}>Rusak</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Stok / Jumlah</label>
                            <input type="number" name="stock" value="{{ old('stock', $item->stock) }}" min="0" class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 focus:ring-2 focus:ring-[#11d4d4]/50 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Kondisi Fisik</label>
                            <div class="relative">
                                <select name="condition" class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 cursor-pointer">
                                    <option value="good" {{ $item->condition == 'good' ? 'selected' : '' }}>Baik</option>
                                    <option value="fair" {{ $item->condition == 'fair' ? 'selected' : '' }}>Layak</option>
                                    <option value="poor" {{ $item->condition == 'poor' ? 'selected' : '' }}>Buruk</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Lokasi Penyimpanan</label>
                        <input type="text" name="location" value="{{ old('location', $item->location) }}" class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 focus:ring-2 focus:ring-[#11d4d4]/50 transition-all">
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Foto Barang</label>
                    
                    <div class="relative flex-1 min-h-[300px] w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2.5rem] hover:bg-slate-100 hover:border-[#11d4d4]/50 transition-all overflow-hidden group">
                        
                        <input type="file" name="image" accept="image/*" @change="fileChosen" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div x-show="!imageUrl" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform duration-300">
                                <span class="material-symbols-outlined text-3xl text-slate-400 group-hover:text-[#11d4d4]">add_a_photo</span>
                            </div>
                            <p class="text-sm font-bold text-slate-600">Pilih foto baru</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Akan menimpa foto lama</p>
                        </div>

                        <div x-show="imageUrl" class="absolute inset-0 bg-white" style="display: none;">
                            <img :src="imageUrl" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity backdrop-blur-sm">
                                <span class="bg-white text-slate-800 text-xs font-bold px-4 py-2 rounded-full shadow-lg">Ganti Foto</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <hr class="border-slate-100 my-8">
            <div class="flex justify-end gap-4">
                <a href="{{ route('items.index') }}" class="px-8 py-3.5 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-full transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-[#11d4d4] hover:bg-[#0eb8b8] text-white font-black px-10 py-3.5 rounded-full text-sm transition-all shadow-lg shadow-[#11d4d4]/30 uppercase tracking-widest">
                    Update Barang
                </button>
            </div>
        </form>
    </div>
</x-app-layout>