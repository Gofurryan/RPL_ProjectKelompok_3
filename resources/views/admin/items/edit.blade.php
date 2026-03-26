<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="item_code" class="block text-sm font-medium text-gray-700">Kode Barang</label>
                            <input type="text" name="item_code" id="item_code" value="{{ $item->item_code }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" name="name" id="name" value="{{ $item->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="Elektronik" {{ $item->category == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="Furniture" {{ $item->category == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="Perlengkapan Ibadah" {{ $item->category == 'Perlengkapan Ibadah' ? 'selected' : '' }}>Perlengkapan Ibadah</option>
                                <option value="Sarana Umum" {{ $item->category == 'Sarana Umum' ? 'selected' : '' }}>Sarana Umum</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700">Lokasi Penyimpanan</label>
                            <input type="text" name="location" id="location" value="{{ $item->location }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Barang</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="Available" {{ $item->status == 'Available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="Borrowed" {{ $item->status == 'Borrowed' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="Maintenance" {{ $item->status == 'Maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                                <option value="Damaged" {{ $item->status == 'Damaged' ? 'selected' : '' }}>Rusak</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="condition" class="block text-sm font-medium text-gray-700">Kondisi Fisik</label>
                            <select name="condition" id="condition" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="good" {{ $item->condition == 'good' ? 'selected' : '' }}>Baik</option>
                                <option value="fair" {{ $item->condition == 'fair' ? 'selected' : '' }}>Layak (Ada Minus Sedikit)</option>
                                <option value="poor" {{ $item->condition == 'poor' ? 'selected' : '' }}>Buruk (Perlu Perbaikan)</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-6">
                            <a href="{{ route('items.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Batal</a>
                            <x-primary-button>
                                {{ __('Perbarui Barang') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>