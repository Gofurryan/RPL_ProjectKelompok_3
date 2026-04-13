<x-app-layout>
    <x-slot name="header">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($errors->has('conflict'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Gagal Booking: Jadwal Bentrok!</p>
                    <p>{{ $errors->first('conflict') }}</p>
                </div>
            @endif

            @if ($errors->any() && !$errors->has('conflict'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Peringatan Input Form:</p>
                    <ul class="list-disc pl-5 mt-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Form Pengajuan Peminjaman Barang</h3>
                    
                    <form action="{{ route('warga.booking.store') }}" method="POST">
                        @csrf

                        <div id="items-container" class="space-y-4">
                            <label class="block font-medium text-sm text-gray-700">Barang yang Ingin Dipinjam</label>
                            
                            <div class="item-row flex items-center space-x-4 bg-gray-50 p-4 rounded-lg border">
                                <div class="flex-1">
                                    <x-input-label value="Pilih Barang" />
                                    <select name="items[0][id]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-24">
                                    <x-input-label value="Jumlah" />
                                    <x-text-input type="number" name="items[0][qty]" value="1" min="1" class="block mt-1 w-full" required />
                                </div>
                                <div class="pt-6">
                                    <span class="text-gray-400 text-sm italic">Wajib</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="button" id="add-item-btn" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                + Tambah Barang Lain
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="loan_date" class="block text-sm font-medium text-gray-700">Rencana Tanggal Diambil</label>
                                <input type="datetime-local" name="loan_date" id="loan_date" value="{{ old('loan_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Rencana Tanggal Dikembalikan</label>
                                <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Ajukan Booking') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
    let rowIndex = 1; // Mulai dari 1 karena indeks 0 sudah ada secara default

    document.getElementById('add-item-btn').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        
        // Buat elemen baris baru
        const newRow = document.createElement('div');
        newRow.className = 'item-row flex items-center space-x-4 bg-gray-50 p-4 rounded-lg border mt-4 animate-fade-in';
        
        // Isi HTML baris baru dengan index yang dinamis
        newRow.innerHTML = `
            <div class="flex-1">
                <x-input-label value="Pilih Barang" />
                <select name="items[${rowIndex}][id]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">-- Pilih --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-24">
                <x-input-label value="Jumlah" />
                <x-text-input type="number" name="items[${rowIndex}][qty]" value="1" min="1" class="block mt-1 w-full" required />
            </div>
            <div class="pt-6">
                <button type="button" class="remove-row-btn text-red-600 hover:text-red-900 font-bold">Hapus</button>
            </div>
        `;
        
        container.appendChild(newRow);
        rowIndex++;
    });

    // Fungsi untuk menghapus baris
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row-btn')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
</x-app-layout>