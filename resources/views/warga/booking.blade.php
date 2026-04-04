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

                        <div class="mb-4">
                            <label for="item_id" class="block text-sm font-medium text-gray-700">Pilih Barang</label>
                            <select name="item_id" id="item_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Barang yang Tersedia --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        [{{ $item->item_code }}] {{ $item->name }} - Kondisi: {{ ucfirst($item->condition) }}
                                    </option>
                                @endforeach
                            </select>
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
</x-app-layout>