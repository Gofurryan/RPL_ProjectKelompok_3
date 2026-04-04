<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Peminjaman Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-3 px-4">Tgl Pengajuan</th>
                                <th class="py-3 px-4">Peminjam</th>
                                <th class="py-3 px-4">Barang</th>
                                <th class="py-3 px-4">Jadwal Pinjam & Kembali</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($loans as $loan)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-4 px-4">{{ $loan->created_at->format('d M Y') }}</td>
                                    <td class="py-4 px-4 font-bold text-gray-800">{{ $loan->user->name }}</td>
                                    <td class="py-4 px-4 font-medium text-indigo-600">[{{ $loan->item->item_code }}] <br> {{ $loan->item->name }}</td>
                                    <td class="py-4 px-4">
                                        <span class="text-green-600 font-bold">Ambil:</span> {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }} <br>
                                        <span class="text-red-600 font-bold">Kembali:</span> {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($loan->status == 'Pending')
                                            <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold">Menunggu</span>
                                        @elseif($loan->status == 'Approved')
                                            <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold">Disetujui</span>
                                        @elseif($loan->status == 'Rejected')
                                            <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold">Ditolak</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-full text-xs font-semibold">{{ $loan->status }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($loan->status == 'Pending')
                                            <div class="flex items-center justify-center space-x-2">
                                                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs" onclick="return confirm('Setujui peminjaman ini?');">Setujui</button>
                                                </form>
                                            
                                            <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs" onclick="return confirm('Tolak peminjaman ini?');">Tolak</button>
                                            </form>
                                        </div>
                                        @elseif($loan->status == 'Approved' || $loan->status == 'Active')
                                            <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs" onclick="return confirm('Konfirmasi barang telah dikembalikan fisik?');">Terima Pengembalian</button>
                                            </form>
                                            
                                        @elseif($loan->status == 'Returned')
                                            @if($loan->penalty)
                                                <span class="text-red-600 font-bold text-xs">Denda: Rp {{ number_format($loan->penalty->amount, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-green-600 font-bold text-xs">Selesai (Tepat Waktu)</span>
                                            @endif
                                            
                                        @else
                                            <span class="text-gray-400 text-xs italic">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 px-6 text-center text-gray-500">Belum ada pengajuan peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>