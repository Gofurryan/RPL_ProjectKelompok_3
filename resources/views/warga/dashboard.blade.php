<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Peminjaman Warga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->has('conflict'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Gagal Booking: Jadwal Bentrok!</p>
                    <p>{{ $errors->first('conflict') }}</p>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Riwayat Peminjaman Saya</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b border-gray-200 text-gray-600 uppercase text-xs">
                                    <th class="py-3 px-4">Nama Barang</th>
                                    <th class="py-3 px-4">Tgl Diambil</th>
                                    <th class="py-3 px-4">Tgl Kembali</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($myLoans as $loan)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium">{{ $loan->item->name }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y, H:i') }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($loan->status == 'Pending')
                                                <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Menunggu</span>
                                            @elseif($loan->status == 'Approved')
                                                <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Disetujui</span>
                                            @elseif($loan->status == 'Active')
                                                <span class="bg-green-100 text-green-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Dibawa</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-md text-xs font-bold uppercase">{{ $loan->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 px-4 text-center text-gray-500">Anda belum pernah melakukan peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>