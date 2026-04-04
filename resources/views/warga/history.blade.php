<x-app-layout>
    <x-slot name="header">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
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