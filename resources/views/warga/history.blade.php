<x-app-layout>
    <x-slot name="header">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Berhasil!</p>
                    @foreach (session('success') as $msg)
                        <p>{{ $msg }}</p>
                    @endforeach
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
                                @forelse($loans as $loan)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-6 py-4">
    <ul class="space-y-1">
        @foreach($loan->details as $detail)
            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                <span class="w-1.5 h-1.5 rounded-full bg-primary block"></span>
                <span class="font-semibold">{{ $detail->item->name }}</span> 
                <span class="text-xs text-slate-500">({{ $detail->qty }} unit)</span>
            </li>
        @endforeach
    </ul>
    
    @if($loan->details->isEmpty())
        <span class="text-xs text-red-500 italic">Data barang tidak ditemukan</span>
    @endif
</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y, H:i') }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($loan->status == 'Pending')
                                            <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Menunggu</span>
                                            @elseif($loan->status == 'Approved')
                                            <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Disetujui</span>
                                            @elseif($loan->status == 'Active')
                                            <span class="bg-indigo-100 text-indigo-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Sedang Dipinjam</span>
                                            @elseif($loan->status == 'Returned')
                                            <span class="bg-green-100 text-green-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Dikembalikan</span>
                                            @elseif($loan->status == 'Rejected')
                                            <span class="bg-red-100 text-red-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Ditolak</span>
                                            @elseif($loan->status == 'Overdue')
                                            <span class="bg-orange-100 text-orange-800 py-1 px-3 rounded-md text-xs font-bold uppercase">Terlambat</span>
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