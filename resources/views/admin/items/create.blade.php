<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Barang Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('items.store') }}" method="POST">
                        @csrf <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori Barang</label>
                            <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Perlengkapan Ibadah">Perlengkapan Ibadah</option>
                                <option value="Sarana Umum">Sarana Umum</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Awal</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="Available">Tersedia</option>
                                <option value="Damaged">Rusak</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">*Barang baru otomatis tidak dalam status 'Dipinjam'</p>
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-6">
                            <a href="{{ route('items.index') }}" class="text-gray-500 hover:text-gray-700 mr-4 text-sm font-medium">Batal</a>
    
                            <x-primary-button>
                                {{ __('Simpan Barang') }}
                            </x-primary-button>
                        </div>

                        <div class="mb-6">
                            <label for="condition" class="block text-sm font-medium text-gray-700">Kondisi Fisik</label>
                            <select name="condition" id="condition" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="good">Baik</option>
                                <option value="fair">Layak (Ada Minus Sedikit)</option>
                                <option value="poor">Buruk (Perlu Perbaikan)</option>
                            </select>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>