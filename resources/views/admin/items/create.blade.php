<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Barang Baru') }}
        </h2>
    </x-slot>

    <div class="mb-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Tambah Inventaris</h1>
                <p class="text-sm text-slate-500 mt-1">Masukkan detail barang dan unggah foto operasional</p>
            </div>
            <a href="{{ route('items.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-full text-sm hover:bg-slate-50 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" 
              x-data="{ 
                  imageUrl: null,
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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <div class="lg:col-span-8 space-y-6">
                    
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Kipas Angin Miyako" class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 focus:ring-2 focus:ring-[#11d4d4]/50 transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Kategori <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="category" required class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 cursor-pointer">
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="Perlengkapan Ibadah">Perlengkapan Ibadah</option>
                                    <option value="Sarana Umum">Sarana Umum</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Status <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="status" required class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 cursor-pointer">
                                    <option value="Available">Tersedia</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Stok / Jumlah</label>
                            <input type="number" name="stock" min="1" value="1" class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 focus:ring-2 focus:ring-[#11d4d4]/50 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Kondisi Fisik</label>
                            <div class="relative">
                                <select name="condition" class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 appearance-none focus:ring-2 focus:ring-[#11d4d4]/50 cursor-pointer">
                                    <option value="good">Baik</option>
                                    <option value="fair">Layak</option>
                                    <option value="poor">Buruk</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-4">Lokasi Penyimpanan</label>
                        <input type="text" name="location" placeholder="Contoh: Gudang Utama Lantai 1" class="w-full bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-full py-4 px-6 focus:ring-2 focus:ring-[#11d4d4]/50 transition-all">
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
                            <p class="text-sm font-bold text-slate-600">Klik atau Drag foto ke sini</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Maks 2MB (JPG, PNG)</p>
                        </div>

                        <div x-show="imageUrl" class="absolute inset-0 bg-white" style="display: none;">
                            <img :src="imageUrl" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
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
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</x-app-layout>